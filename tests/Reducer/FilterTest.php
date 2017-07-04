<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer\Filter;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class FilterTest extends TestCase
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

        $this->assertEquals([2, 4], $squared);
    }
}
