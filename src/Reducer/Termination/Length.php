<?php


namespace Pitchart\Transformer\Reducer\Termination;


use Pitchart\Transformer\Termination;

class Length implements Termination
{
    public function init()
    {
        return 0;
    }

    public function step($result, $current)
    {
        return ++$result;
    }

    public function complete($result)
    {
        return $result;
    }
}