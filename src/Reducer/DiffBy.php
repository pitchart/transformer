<?php


namespace Pitchart\Transformer\Reducer;


use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer;

class DiffBy extends Diff
{
    public function __construct(Reducer $next, iterable $collection, callable $callback)
    {
        parent::__construct($next, $collection, comparator($callback));
    }
}