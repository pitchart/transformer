<?php

namespace Pitchart\Transformer\Tests\Reducer;

use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer\Intersect;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class IntersectTest extends TestCase
{
    public function test_computes_the_differences_collection()
    {
        $intersection = (new Transformer(range(1, 4)))
            ->intersect(range(3, 6))
            ->toArray();

        self::assertEquals([3, 4], $intersection);
    }

    public function test_computes_the_differences_collection_using_a_callable()
    {
        $intersection = (new Transformer(range(1, 6)))
            ->intersect([1, 2], comparator(function ($item) { return $item % 3;}))
            ->toArray();

        self::assertEquals([1, 2, 4, 5], $intersection);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4]));
        $copy = clone $transformer;

        self::assertEquals([3, 4], $transformer->intersect(range(3, 6))->toArray());
        self::assertEquals([3, 4], $transformer->intersect(range(3, 6))->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_on_arrays()
    {
        $intersection = t\intersect(range(3, 6), null, range(1, 4));
        self::assertEquals([3, 4], $intersection);
    }

    public function test_applies_on_arrays_with_callback()
    {
        $intersection = t\intersect([1, 2], comparator(function ($item) { return $item % 3;}), range(1, 6));
        self::assertEquals([1, 2, 4, 5], $intersection);
    }

    public function test_applies_on_iterator()
    {
        $intersection = t\intersect(new \ArrayIterator(range(3, 6)), null, new \ArrayIterator(range(1, 4)));
        self::assertEquals([3, 4], $intersection);
    }

    public function test_applies_on_iterator_with_callback()
    {
        $intersection = t\intersect(
            new \ArrayIterator(range(1, 2)),
            comparator(function ($item) { return $item % 3;}),
            new \ArrayIterator(range(1, 6))
        );
        self::assertEquals([1, 2, 4, 5], $intersection);
    }
}
