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
use Pitchart\Transformer\Reducer\TakeNth;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class TakeNthTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\take_nth(3)(t\to_array()));
    }

    public function test_extracts_every_nth_items_of_a_collection()
    {
        $extracted = (new Transformer(range(1, 9)))
            ->takeNth(3)->toArray();
        self::assertEquals([3, 6 , 9], $extracted);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 9)));
        $copy = clone $transformer;
        $expected = [3, 6, 9];

        self::assertEquals($expected, $transformer->takeNth(3)->toArray());
        self::assertEquals($expected, $transformer->takeNth(3)->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $extracted = t\take_nth(3, range(1, 9));
        self::assertEquals([3, 6 , 9], $extracted);
    }

    public function test_applies_to_iterators()
    {
        $extracted = t\take_nth(3, new \ArrayIterator(range(1, 9)));
        self::assertEquals([3, 6 , 9], $extracted);
    }
}
