<?php

namespace app\helpers\report;

use app\helpers\FileHelper;

class TxtFileReporter extends Reporter implements ReporterInterface
{
    public function run()
    {
        $reportDirPath = __DIR__ . '/../../../reports';
        $dir = FileHelper::getOrCreateDir($reportDirPath);

        $fileName = 'Report_' . date('Y-m-d-h-m-s') . '.txt';
        file_put_contents($dir . '/' . $fileName, $this->getReportContent());
    }

    private function getReportContent()
    {
        $report = "Scanned '{$this->path}'\n\n";
        if (!$this->list) {
            $report .= 'Doubles not found';
            return $report;
        }
        $report .= "Doubles:\n";
        $report .= implode("\n", $this->list);
        return $report;
    }
}
