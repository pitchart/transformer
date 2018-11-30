<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;

class Partition implements Reducer
{
    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var int
     */
    private $size;

    /**
     * @var array
     */
    private $buffer = [];

    /**
     * Partition constructor.
     *
     * @param Reducer $next
     * @param int $size
     */
    public function __construct(Reducer $next, $size)
    {
        $this->next = $next;
        $this->size = $size;
    }

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        $this->buffer[] = $current;
        if (count($this->buffer) == $this->size) {
            $result = $this->next->step($result, $this->buffer);
            $this->buffer = [];

            return $result;
        }

        return $result;
    }

    public function complete($result)
    {
        if (count($this->buffer)) {
            $result = $this->next->step($result, $this->buffer);
            $this->buffer = [];
        }

        return $this->next->complete($result);
    }
}
