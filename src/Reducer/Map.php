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
        $callback = $this->callback;
        return $this->next->step($result, $callback($current));
    }
}
