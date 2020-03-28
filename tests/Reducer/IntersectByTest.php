<?php

namespace Pitchart\Transformer\Tests\Reducer;

use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer\Intersect;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class IntersectByTest extends TestCase
{
    public function test_computes_the_differences_collection()
    {
        $intersection = (new Transformer(range(1, 6)))
            ->intersectBy([1, 2], function ($item) { return $item % 3;})
            ->toArray();

        self::assertEquals([1, 2, 4, 5], $intersection);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 6)));
        $copy = clone $transformer;

        self::assertEquals([1, 2, 4, 5], $transformer->intersectBy([1, 2], function ($item) { return $item % 3;})->toArray());
        self::assertEquals([1, 2, 4, 5], $transformer->intersectBy([1, 2], function ($item) { return $item % 3;})->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_on_arrays()
    {
        $intersection = t\intersectBy([1, 2], function ($item) { return $item % 3;}, range(1, 6));
        self::assertEquals([1, 2, 4, 5], $intersection);
    }

    public function test_applies_on_iterator()
    {
        $intersection = t\intersectBy(
            new \ArrayIterator(range(1, 2)),
            function ($item) { return $item % 3;},
            new \ArrayIterator(range(1, 6))
        );
        self::assertEquals([1, 2, 4, 5], $intersection);
    }
}
