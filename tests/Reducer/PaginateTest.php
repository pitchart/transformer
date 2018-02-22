<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Paginate;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class PaginateTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\paginate()(t\to_array()));
    }

    public function test_paginates_over_items()
    {
        $paginated = (new Transformer(range(1,6)))
            ->paginate(2, 2)
            ->toArray();
        self::assertEquals([3, 4], $paginated);
    }

    public function test_applies_to_arrays()
    {
        $paginated = t\paginate(2, 2, range(1,6));
        self::assertEquals([3, 4], $paginated);
    }

    public function test_applies_to_iterators()
    {
        $paginated = t\paginate(2, 2, new \ArrayIterator(range(1,6)));
        self::assertEquals([3, 4], $paginated);
    }
}
