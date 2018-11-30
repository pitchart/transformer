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
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\Tests\Fixtures\is_even;

/**
 * @internal
 */
final class RemoveTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $removing = t\remove(is_even());

        self::assertInstanceOf(Reducer::class, $removing(t\to_array()));
    }

    public function test_removes_items_with_a_callback()
    {
        $squared = (new Transformer(range(1, 4)))
            ->remove(is_even())
            ->toArray();

        self::assertEquals([1, 3], $squared);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 4)));
        $copy = clone $transformer;
        $expected = [1, 3];

        self::assertEquals($expected, $transformer->remove(is_even())->toArray());
        self::assertEquals($expected, $transformer->remove(is_even())->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $odd = t\remove(is_even(), [1, 2, 3, 4]);
        self::assertEquals([1, 3], $odd);
    }

    public function test_applies_to_iterators()
    {
        $odd = t\remove(is_even(), new \ArrayIterator([1, 2, 3, 4]));
        self::assertEquals([1, 3], $odd);
    }
}
