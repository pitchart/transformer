<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;

class Drop implements Reducer
{
    /**
     * @var Reducer
     */
    protected $next;

    /**
     * @var integer
     */
    protected $number;

    /**
     * @var integer
     */
    protected $remaining = 0;

    public function __construct(Reducer $next, int $number)
    {
        $this->next = $next;
        $this->number = $number;
    }

    public function init()
    {
        $this->remaining = 0;

        return $this->next->init();
    }

    public function step($result, $current)
    {
        $this->remaining++;
        if ($this->remaining > $this->number) {
            return $this->next->step($result, $current);
        }

        return $result;
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }
}
