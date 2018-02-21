<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\DropWhile;
use PHPUnit\Framework\TestCase;
use function Pitchart\Transformer\Tests\is_lower_than_four;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class DropWhileTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\drop_while(is_lower_than_four())(t\to_array()));
    }

    public function test_drops_items_while_a_predicat_is_true()
    {
        $dropped = (new Transformer(range(1, 6)))
            ->dropWhile(is_lower_than_four())->toArray();
        self::assertEquals([4, 5, 6], $dropped);
    }

    public function test_applies_to_arrays()
    {
        $dropped = t\drop_while(is_lower_than_four(), range(1, 6));
        self::assertEquals([4, 5, 6], $dropped);
    }

    public function test_applies_to_iterators()
    {
        $dropped = t\drop_while(is_lower_than_four(), new \ArrayIterator(range(1, 6)));
        self::assertEquals([4, 5, 6], $dropped);
    }


}
