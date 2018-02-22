<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Take;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class TakeTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\take(3)(t\to_array()));
    }

    public function test_extracts_a_number_of_values_from_a_collection()
    {
        $extracted = (new Transformer(range(1, 6)))
            ->take(3)->toArray();

        self::assertEquals([1,2,3], $extracted);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 6)));
        $copy = clone $transformer;

        self::assertEquals([1,2,3], $transformer->take(3)->toArray());
        self::assertEquals([1,2,3], $transformer->take(3)->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $extracted = t\take(3, range(1, 6));
        self::assertEquals([1,2,3], $extracted);
    }

    public function test_applies_to_iterators()
    {
        $extracted = t\take(3, new \ArrayIterator(range(1, 6)));
        self::assertEquals([1,2,3], $extracted);
    }
}
