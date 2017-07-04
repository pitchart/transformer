<?php

namespace Pitchart\Transformer\Reducer\Termination;

use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Termination;

class SingleResult implements Termination
{
    public function init()
    {
        return;
    }

    public function step($result, $current)
    {
        return new Reduced($current);
    }

    public function complete($result)
    {
        if ($result instanceof Reduced) {
            return $result->value();
        }
        return $result;
    }
}
