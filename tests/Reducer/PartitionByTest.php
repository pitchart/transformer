<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\PartitionBy;
use PHPUnit\Framework\TestCase;
use function Pitchart\Transformer\Tests\is_even;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

class PartitionByTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\partition(4)(t\to_array()));
    }

    public function test_partitions_a_collection_by_a_callback()
    {
        $partitioned = (new Transformer([0, 2, 3, 1, 4, 5, 6, 8, 9, 7]))
            ->partitionBy(is_even())->toArray();

        self::assertEquals([[0, 2], [3, 1], [4], [5], [6,8], [9, 7]], $partitioned);
    }

    public function test_applies_to_arrays()
    {
        $partitioned = t\partition_by(is_even(), [0, 2, 3, 1, 4, 5, 6, 8, 9, 7]);
        self::assertEquals([[0, 2], [3, 1], [4], [5], [6,8], [9, 7]], $partitioned);
    }

    public function test_applies_to_iterators()
    {
        $partitioned = t\partition_by(is_even(), new \ArrayIterator([0, 2, 3, 1, 4, 5, 6, 8, 9, 7]));
        self::assertEquals([[0, 2], [3, 1], [4], [5], [6,8], [9, 7]], $partitioned);
    }
}
