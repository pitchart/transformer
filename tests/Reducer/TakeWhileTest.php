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
use Pitchart\Transformer\Reducer\TakeWhile;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\Tests\Fixtures\is_lower_than_four;

/**
 * @internal
 */
final class TakeWhileTest extends TestCase
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
