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
use Pitchart\Transformer\Reducer\Take;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class TakeTest extends TestCase
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
