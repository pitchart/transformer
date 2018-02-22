<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Transducer as t;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\Tests\Fixtures\square;

class MapTest extends TestCase
{
    public function test_applies_a_callable_on_each_item()
    {
        $squared = (new Transformer(range(1, 2)))
            ->map(square())
            ->toArray();

        $this->assertEquals([1, 4], $squared);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4, 5]));
        $copy = clone $transformer;

        self::assertEquals([1, 4, 9, 16, 25], $transformer->map(square())->toArray());
        self::assertEquals([1, 4, 9, 16, 25], $transformer->map(square())->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_on_arrays()
    {
        $squared = t\map(square(), range(1,2));
        self::assertEquals([1, 4], $squared);
    }

    public function test_applies_on_iterator()
    {
        $squared = t\map(square(), new \ArrayIterator([1, 2]));
        self::assertEquals([1, 4], $squared);
    }

}
