<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\PartitionBy;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\Tests\Fixtures\is_even;

/**
 * @internal
 */
final class PartitionByTest extends TestCase
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

    public function test_is_immutable()
    {
        $transformer = (new Transformer([0, 2, 3, 1, 4, 5, 6, 8, 9, 7]));
        $copy = clone $transformer;
        $expected = [[0, 2], [3, 1], [4], [5], [6,8], [9, 7]];

        self::assertEquals($expected, $transformer->partitionBy(is_even())->toArray());
        self::assertEquals($expected, $transformer->partitionBy(is_even())->toArray());
        self::assertEquals($transformer, $copy);
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
