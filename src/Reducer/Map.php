<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\HasCallback;
use Pitchart\Transformer\Reducer\Traits\IsStateless;

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
