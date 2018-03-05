<?php

namespace Pitchart\Transformer\Reducer\Termination;

use Pitchart\Transformer\Termination;

class ToString implements Termination
{
    /**
     * @var string
     */
    private $glue;

    public function __construct($glue = '')
    {
        $this->glue = $glue;
    }

    public function init()
    {
        return '';
    }

    public function step($result, $current)
    {
        if ((string) $result) {
            return $result.$this->glue.$current;
        }
        return $current;
    }

    public function complete($result)
    {
        return $result;
    }

}