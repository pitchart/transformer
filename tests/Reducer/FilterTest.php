<?php

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use function Pitchart\Transformer\Tests\Fixtures\is_even;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class FilterTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $filtering = t\filter(is_even());

        self::assertInstanceOf(Reducer::class, $filtering(t\to_array()));
    }

    public function test_filters_items_with_a_callback()
    {
        $squared = (new Transformer(range(1, 4)))
            ->filter(is_even())
            ->toArray();

        $this->assertEquals([2, 4], $squared);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4, 5, 6]));
        $copy = clone $transformer;

        self::assertEquals([2, 4, 6], $transformer->filter(is_even())->toArray());
        self::assertEquals([2, 4, 6], $transformer->filter(is_even())->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $evens = t\filter(is_even(), [1, 2, 3, 4]);
        self::assertEquals([2, 4], $evens);
    }

    public function test_applies_to_iterators()
    {
        $evens = t\filter(is_even(), new \ArrayIterator([1, 2, 3, 4]));
        self::assertEquals([2, 4], $evens);
    }
}
