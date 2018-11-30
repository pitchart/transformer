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
use Pitchart\Transformer\Reducer\Drop;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class DropTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\drop(3)(t\to_array()));
    }

    public function test_drops_a_number_of_values_from_a_collection()
    {
        $dropped = (new Transformer(range(1, 6)))
            ->drop(4)->toArray();

        self::assertEquals([5,6], $dropped);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 2, 4, 6]));
        $copy = clone $transformer;

        self::assertEquals([4,6], $transformer->drop(4)->toArray());
        self::assertEquals([4,6], $transformer->drop(4)->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $dropped = t\drop(4, range(1, 6));
        self::assertEquals([5,6], $dropped);
    }

    public function test_applies_to_iterators()
    {
        $dropped = t\drop(4, new \ArrayIterator(range(1, 6)));
        self::assertEquals([5, 6], $dropped);
    }
}
