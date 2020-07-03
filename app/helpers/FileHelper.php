<?php
namespace app\helpers;

use app\exceptions\NotDirectoryException;
use app\exceptions\NotFoundException;
use app\exceptions\PermissionException;
use app\exceptions\SomethingWrongException;
use Generator;
use RuntimeException;

class FileHelper
{
    /**
     * @var string
     */
    private $path;

    /**
     * FileHelper constructor.
     * @param string $path
     * @throws NotDirectoryException
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function __construct($path)
    {
        $this->setPath($path);
    }

    /**
     * @param string $path
     * @throws NotDirectoryException
     * @throws NotFoundException
     * @throws PermissionException
     */
    public function setPath($path)
    {
        $path = realpath($path);

        if (!file_exists($path)) {
            throw new NotFoundException('Path should exists');
        }

        if (!is_dir($path)) {
            throw new NotDirectoryException('Path should be a directory');
        }

        if (!is_readable($path)) {
            throw new PermissionException('Permission deny');
        }

        $this->path = $path;
    }

    /**
     * @param bool $recursive
     * @return Generator
     * @throws SomethingWrongException
     */
    public function getFilesList($recursive = false)
    {
        yield from $this->getFileListGenerator($this->path, $recursive);
    }

    /**
     * @param string $path
     * @param boolean $recursive
     * @return Generator
     * @throws SomethingWrongException
     */
    private function getFileListGenerator($path, $recursive)
    {
        if (!$handle = opendir($path)) {
            throw new SomethingWrongException('Can\'t open path for scan');
        }

        while (false !== ($entry = readdir($handle))) {
            $pathInternal = "{$path}/{$entry}";
            if (is_file($pathInternal)) {
                yield $pathInternal;
            }
            if ($recursive && is_dir($pathInternal) && !in_array($entry, ['.', '..'])) {
                yield from $this->getFileListGenerator($pathInternal, $recursive);
            }
        }

        closedir($handle);
    }

    /**
     * @param $path
     * @return mixed
     */
    public static function getOrCreateDir($path)
    {
        $path = realpath($path);
        if (file_exists($path) && is_dir($path)) {
            return static::getWritableDir($path);
        }
        if (!mkdir($path) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        return static::getWritableDir($path);
    }

    /**
     * @param string $path
     * @return string
     */
    private static function getWritableDir($path)
    {
        if (is_writable($path)) {
            return $path;
        }
        chmod($path, 0755);
        if (!is_writable($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not writable', $path));
        }
        return $path;
    }
}
