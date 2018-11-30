<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Flatten;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class FlattenTest extends TestCase
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
        $transformer = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]));
        $copy = clone $transformer;

        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $transformer->flatten()->toArray());
        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $transformer->flatten()->toArray());
        self::assertEquals($transformer, $copy);
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
