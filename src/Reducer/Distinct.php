<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;

class Distinct implements Reducer
{
    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var array
     */
    private $distincts = [];

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
        if (!in_array($current, $this->distincts, true)) {
            $this->distincts[] = $current;

            return $this->next->step($result, $current);
        }

        return $result;
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }
}
