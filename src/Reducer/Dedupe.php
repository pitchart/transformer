<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;

class Dedupe implements Reducer
{
    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var null|mixed
     */
    private $last;

    /**
     * @var bool
     */
    private $started = false;

    public function __construct(Reducer $next)
    {
        $this->next = $next;
    }

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        if (!$this->started || $current !== $this->last) {
            $return = $this->next->step($result, $current);
        } else {
            $return = $result;
        }
        $this->started = true;
        $this->last = $current;

        return $return;
    }

    public function complete($result)
    {
        $this->last = null;
        $this->started  = false;

        return $this->next->complete($result);
    }
}
