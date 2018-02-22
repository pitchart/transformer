<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\TakeWhile;
use PHPUnit\Framework\TestCase;
use function Pitchart\Transformer\Tests\is_lower_than_four;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class TakeWhileTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\take_while(is_lower_than_four())(t\to_array()));
    }

    public function test_extracts_as_long_as_callback_is_valid()
    {
        $extracted = (new Transformer(range(1, 6)))
            ->takeWhile(is_lower_than_four())->toArray();

        self::assertEquals([1,2,3], $extracted);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 9)));
        $copy = clone $transformer;
        $expected = [1, 2, 3];

        self::assertEquals($expected, $transformer->takeWhile(is_lower_than_four())->toArray());
        self::assertEquals($expected, $transformer->takeWhile(is_lower_than_four())->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $extracted = t\take_while(is_lower_than_four(), range(1, 6));
        self::assertEquals([1,2,3], $extracted);
    }

    public function test_applies_to_iterators()
    {
        $extracted = t\take_while(is_lower_than_four(), new \ArrayIterator(range(1, 6)));
        self::assertEquals([1,2,3], $extracted);
    }
}
