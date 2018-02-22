<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Partition;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

class PartitionTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\partition(4)(t\to_array()));
    }

    public function test_paginates_over_items()
    {
        $partitioned = (new Transformer(range(1,9)))
            ->partition(3)->toArray();

        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }

    public function test_applies_to_arrays()
    {
        $partitioned = t\partition(3, range(1,9));
        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }

    public function test_applies_to_iterators()
    {
        $partitioned = t\partition(3, new \ArrayIterator(range(1,9)));
        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }
}
