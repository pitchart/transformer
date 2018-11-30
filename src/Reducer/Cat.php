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

class Cat implements Reducer
{
    use IsStateless;

    /**
     * @var Reducer
     */
    private $next;

    public function __construct(Reducer $next)
    {
        $this->next = $next;
    }

    public function step($result, $current)
    {
        if (!is_array($current)
            && !($current instanceof \Traversable)
        ) {
            return $this->next->step($result, $current);
        }
        foreach ($current as $item) {
            $result = $this->next->step($result, $item);
        }

        return $result;
    }
}
