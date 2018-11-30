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
final class FilterTest extends TestCase
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

        self::assertEquals([2, 4], $squared);
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
