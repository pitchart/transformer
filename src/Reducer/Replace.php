<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;

class Replace implements Reducer
{
    use IsStateless;

    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var array
     */
    private $map;

    /**
     * Replace constructor.
     *
     * @param Reducer $next
     * @param array $map
     */
    public function __construct(Reducer $next, array $map)
    {
        $this->next = $next;
        $this->map = $map;
    }

    public function step($result, $current)
    {
        if (isset($this->map[$current])) {
            return $this->next->step($result, $this->map[$current]);
        }

        return $this->next->step($result, $current);
    }
}
