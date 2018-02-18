<?php

namespace Pitchart\Transformer\Reducer\Termination;


use Pitchart\Transformer\Termination;

class ToIterator implements Termination
{

    private $buffer = [];

    public function init()
    {
        return [];
    }

    public function step($result, $current)
    {
        $this->buffer[] = $current;
        return $result;
    }

    public function complete($result)
    {
        yield from $this->buffer;
    }

}