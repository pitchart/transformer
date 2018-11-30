<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class GroupBy implements Reducer
{
    use HasCallback;

    private $grouped = [];

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        $key = ($this->callback)($current);
        if (!array_key_exists($key, $this->grouped)) {
            $this->grouped[$key] = [];
        }

        $this->grouped[$key][] = $current;

        return $result;
    }

    public function complete($result)
    {
        if (count($this->grouped)) {
            $result = [];
            foreach ($this->grouped as $key => $group) {
                $item = $this->next->step($result, $group);
                $result[$key] = $item instanceof Reduced ? $item->value() : $item;
            }
        }

        return $this->next->complete($result);
    }
}
