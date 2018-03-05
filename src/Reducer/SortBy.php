<?php

namespace Pitchart\Transformer\Reducer;

use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer;

class SortBy extends Sort
{
    public function __construct(Reducer $next, callable $callback)
    {
        parent::__construct($next, comparator($callback));

    }
}