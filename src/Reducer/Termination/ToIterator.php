<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

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
