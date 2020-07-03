<?php


namespace app\helpers;


use app\exceptions\NotDirectoryException;
use app\exceptions\NotFoundException;
use app\exceptions\PermissionException;
use app\exceptions\SomethingWrongException;
use app\helpers\report\ReporterInterface;
use Exception;

class DoublesHelper
{
    /**
     * @var array
     */
    private $correctFiles = [];

    /**
     * @var array
     */
    private $doubles = [];

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     * @throws SomethingWrongException
     * @throws NotDirectoryException
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function findDoubles($path)
    {
        $this->path = $path;
        foreach ((new FileHelper($path))->getFilesList() as $filePath) {
            $this->checkForDouble($filePath);
        }
    }

    /**
     * @param $path
     */
    private function checkForDouble($path)
    {
        $key = $this->getFileKeyV2($path);
        if (!array_key_exists($key, $this->correctFiles)) {
            $this->correctFiles[$key] = $path;
            return;
        }
        $this->doubles[] = $path;
    }

    /**
     * @param string $path
     * @return string
     */
    private function getFileKey($path)
    {
        $info = [
            mime_content_type($path),
            filesize($path),
        ];
        return implode('_', $info);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getFileKeyV1($path)
    {
        return md5_file($path);
    }

    /**
     * @param string $path
     * @return string
     */
    private function getFileKeyV2($path)
    {
        try {
            $result = explode("  ", shell_exec("md5sum '{$path}'"));
        } catch (Exception $e) {
            return $this->getFileKeyV1($path);
        }
        return $result[0];
    }

    /**
     * @return array
     */
    public function getDoubles()
    {
        return $this->doubles;
    }

    /**
     * @param ReporterInterface $reporter
     */
    public function report(ReporterInterface $reporter)
    {
        $reporter->setSource($this->path);
        $reporter->setList($this->doubles);
        $reporter->run();
    }
}
