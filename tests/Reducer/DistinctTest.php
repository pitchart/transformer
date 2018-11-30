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
use Pitchart\Transformer\Reducer\Distinct;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class DistinctTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $distinct = t\distinct();
        self::assertInstanceOf(Reducer::class, $distinct(t\to_array()));
    }

    public function test_removes_duplicated_values()
    {
        $distincts = t\transduce(t\distinct(), t\to_array(), [1, 2, 3, 2, 4, 6, 5, 1, 0]);
        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 2, 4, 6, 5, 1, 0]));
        $copy = clone $transformer;

        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $transformer->distinct()->toArray());
        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $transformer->distinct()->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_removes_duplicated_values_with_initial_values()
    {
        $distincts = t\transduce(t\distinct(), t\to_array(), [1, 2, 3, 2, 4, 6, 5, 1, 0], [7, 2]);
        self::assertEquals([7, 2, 1, 2, 3, 4, 6, 5, 0], $distincts);
    }

    public function test_applies_to_arrays()
    {
        $distincts = t\distinct([1, 2, 3, 2, 4, 6, 5, 1, 0]);
        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts);
    }

    public function test_applies_to_iterators()
    {
        $distincts = t\distinct(new \ArrayIterator([1, 2, 3, 2, 4, 6, 5, 1, 0]));
        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts);
    }
}
