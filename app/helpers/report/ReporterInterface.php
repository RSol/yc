<?php

namespace app\helpers\report;

interface ReporterInterface
{
    public function setSource($path);
    public function setList(array $list);
    public function run();
}
