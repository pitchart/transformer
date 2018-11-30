<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use function Pitchart\Transformer\comparator;

class SortBy extends Sort
{
    public function __construct(Reducer $next, callable $callback)
    {
        parent::__construct($next, comparator($callback));
    }
}
