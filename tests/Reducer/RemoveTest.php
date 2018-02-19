<?php

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;
use function Pitchart\Transformer\Tests\is_even;

class RemoveTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $removing = t\remove(is_even());

        self::assertInstanceOf(Reducer::class, $removing(t\to_array()));
    }

    public function test_removes_items_with_a_callback()
    {
        $squared = (new Transformer(range(1, 4)))
            ->remove(is_even())
            ->toArray();

        $this->assertEquals([1, 3], $squared);
    }

    public function test_applies_removing_to_arrays()
    {
        $odd = t\remove(is_even(), [1, 2, 3, 4]);
        self::assertEquals([1, 3], $odd);
    }
}
