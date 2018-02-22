<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Drop;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class DropTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\drop(3)(t\to_array()));
    }

    public function test_drops_a_number_of_values_from_a_collection()
    {
        $dropped = (new Transformer(range(1, 6)))
            ->drop(4)->toArray();

        self::assertEquals([5,6], $dropped);
    }

    public function test_applies_to_arrays()
    {
        $dropped = t\drop(4, range(1, 6));
        self::assertEquals([5,6], $dropped);
    }

    public function test_applies_to_iterators()
    {
        $dropped = t\drop(4, new \ArrayIterator(range(1, 6)));
        self::assertEquals([5, 6], $dropped);
    }

}
