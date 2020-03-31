<?php

namespace Pitchart\Transformer\Tests\Reducer;

use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer\Diff;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class DiffByTest extends TestCase
{
    public function test_computes_the_differences_collection()
    {
        $diff = (new Transformer(range(1, 6)))
            ->diffBy([1, 2], function ($item) { return $item % 3;})
            ->toArray();

        self::assertEquals([3, 6], $diff);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 6)));
        $copy = clone $transformer;

        self::assertEquals([3, 6], $transformer->diffBy([1, 2], function ($item) { return $item % 3;})->toArray());
        self::assertEquals([3, 6], $transformer->diffBy([1, 2], function ($item) { return $item % 3;})->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_on_arrays()
    {
        $diff = t\diffBy([1, 2], function ($item) { return $item % 3;}, range(1, 6));
        self::assertEquals([3, 6], $diff);
    }

    public function test_applies_on_iterator()
    {
        $diff = t\diffBy(new \ArrayIterator([1, 2]), function ($item) { return $item % 3;}, new \ArrayIterator(range(1, 6)));
        self::assertEquals([3, 6], $diff);
    }
}
