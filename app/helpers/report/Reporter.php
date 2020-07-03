<?php

namespace app\helpers\report;

class Reporter
{
    /**
     * @var array
     */
    protected $list = [];

    /**
     * @var string
     */
    protected $path;

    /**
     * @param array $list
     */
    public function setList(array $list)
    {
        $this->list = $list;
    }

    /**
     * @param string $path
     */
    public function setSource($path)
    {
        $this->path = $path;
    }
}
