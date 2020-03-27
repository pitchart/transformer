<?php

namespace Pitchart\Transformer\Tests\Reducer;

use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer\Diff;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class DiffTest extends TestCase
{
    public function test_computes_the_differences_collection()
    {
        $diff = (new Transformer(range(1, 4)))
            ->diff(range(3, 6))
            ->toArray();

        self::assertEquals([1, 2], $diff);
    }

    public function test_computes_the_differences_collection_using_a_callable()
    {
        $diff = (new Transformer(range(1, 6)))
            ->diff([1, 2], comparator(function ($item) { return $item % 3;}))
            ->toArray();

        self::assertEquals([3, 6], $diff);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4]));
        $copy = clone $transformer;

        self::assertEquals([1, 2], $transformer->diff(range(3, 6))->toArray());
        self::assertEquals([1, 2], $transformer->diff(range(3, 6))->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_on_arrays()
    {
        $diff = t\diff(range(3, 6), null, range(1, 4));
        self::assertEquals([1, 2], $diff);
    }

    public function test_applies_on_iterator()
    {
        $diff = t\diff(new \ArrayIterator(range(3, 6)), null, new \ArrayIterator(range(1, 4)));
        self::assertEquals([1, 2], $diff);
    }
}
