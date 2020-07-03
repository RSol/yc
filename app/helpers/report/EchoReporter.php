<?php

namespace app\helpers\report;

class EchoReporter extends Reporter implements ReporterInterface
{
    public function run()
    {
        echo $this->getReportContent();
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
        return $report . "\n";
    }
}
