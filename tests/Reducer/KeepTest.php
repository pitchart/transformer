<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Keep;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class KeepTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\keep(function ($item) { return $item; })(t\to_array()));
    }

    public function test_keeps_items_for_which_a_callable_does_not_return_null()
    {
        $kept = (new Transformer([0, 1, null, true, false]))
            ->keep(function ($item) { return $item; })->toArray();

        self::assertEquals([0, 1, true, false], $kept);
    }

    public function test_applies_to_arrays()
    {
        $kept = t\keep(function ($item) { return $item; }, [0, 1, null, true, false]);
        self::assertEquals([0, 1, true, false], $kept);
    }

    public function test_applies_to_iterators()
    {
        $kept = t\keep(function ($item) { return $item; }, new \ArrayIterator([0, 1, null, true, false]));
        self::assertEquals([0, 1, true, false], $kept);
    }
}
