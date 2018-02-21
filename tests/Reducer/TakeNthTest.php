<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\TakeNth;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class TakeNthTest extends TestCase
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
