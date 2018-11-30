<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;

class TakeNth implements Reducer
{
    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var int
     */
    private $frequency;

    /**
     * @var int
     */
    private $increment = 0;

    /**
     * TakeNth constructor.
     *
     * @param Reducer $next
     * @param int $frequency
     */
    public function __construct(Reducer $next, int $frequency)
    {
        $this->next = $next;
        $this->frequency = $frequency;
    }

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        $this->increment++;

        if ($this->increment % $this->frequency) {
            return $result;
        }

        return $this->next->step($result, $current);
    }

    public function complete($result)
    {
        $this->frequency = 0;

        return $this->next->complete($result);
    }
}
