<?php


namespace Pitchart\Transformer\Reducer;


use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer;

class IntersectBy extends Intersect
{
    public function __construct(Reducer $next, $collection, $callback)
    {
        parent::__construct($next, $collection, comparator($callback));
    }

}