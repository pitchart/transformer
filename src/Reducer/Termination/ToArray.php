<?php

namespace Pitchart\Transformer\Reducer\Termination;

use Pitchart\Transformer\Termination;

class ToArray implements Termination
{
    public function init()
    {
        return [];
    }

    public function step($result, $current)
    {
        $result[] = $current;

        return $result;
    }

    public function complete($result)
    {
        return $result;
    }
}
