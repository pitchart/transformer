<?php

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

/**
 * Map transformation
 *
 * @package Pitchart\Transformer\Reducer
 *
 * @author Julien VITTE <julien.vitte@insidegroup.fr>
 */
class Map implements Reducer
{
    use IsStateless,
        HasCallback;

    public function step($result, $current)
    {
        $callable = $this->callable;
        return $this->next->step($result, $callable($current));
    }
}
