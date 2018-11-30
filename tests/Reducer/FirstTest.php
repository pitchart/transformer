<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\Tests\Fixtures\is_greater_than_three;

/**
 * @internal
 */
final class FirstTest extends TestCase
{
    public function test_gets_first_matching_item_of_iterable()
    {
        $first = (new Transformer(range(1, 6)))
            ->first(is_greater_than_three())->single();

        self::assertEquals(4, $first);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4, 5, 6]));
        $copy = clone $transformer;

        self::assertEquals(4, $transformer->first(is_greater_than_three())->single());
        self::assertEquals(4, $transformer->first(is_greater_than_three())->single());
        self::assertEquals($transformer, $copy);
    }

    public function test_gets_first_matching_item_of_arrays()
    {
        $first = t\first(is_greater_than_three(), range(1, 6));
        self::assertEquals(4, $first);
    }

    public function test_gets_first_matching_item_of_iterator()
    {
        $first = t\first(is_greater_than_three(), range(1, 6));
        self::assertEquals(4, $first);
    }
}
