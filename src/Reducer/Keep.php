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

class Keep implements Reducer
{
    use IsStateless,
        HasCallback;

    public function step($result, $current)
    {
        if (($this->callback)($current) !== null) {
            return  $this->next->step($result, $current);
        }

        return $result;
    }
}
