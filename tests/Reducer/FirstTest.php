<?php

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use function Pitchart\Transformer\Tests\is_greater_than_three;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class FirstTest extends TestCase
{

    public function test_gets_first_matching_item_of_iterable()
    {
        $first = (new Transformer(range(1,6)))
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