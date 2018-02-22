<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Flatten;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class FlattenTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\flatten()(t\to_array()));
    }

    public function test_converts_nested_sequences_to_a_single_flat_sequence()
    {
        $flat = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->flatten()->toArray();

        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flat);
    }

    public function test_is_immutable()
    {
        $flatten = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->flatten();

        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flatten->toArray());
        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flatten->toArray());
    }

    public function test_applies_to_arrays()
    {
        $flat = t\flatten([0 ,1, [2, 3], [[4, 5], 6]]);
        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flat);
    }

    public function test_applies_to_iterators()
    {
        $flat = t\flatten(new \ArrayIterator([0 ,1, [2, 3], [[4, 5], 6]]));
        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flat);
    }
}
