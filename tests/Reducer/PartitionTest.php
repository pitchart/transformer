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
use Pitchart\Transformer\Reducer\Partition;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class PartitionTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\partition(4)(t\to_array()));
    }

    public function test_partitions_a_collection()
    {
        $partitioned = (new Transformer(range(1, 9)))
            ->partition(3)->toArray();

        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer(range(1, 9)));
        $copy = clone $transformer;
        $expected = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];

        self::assertEquals($expected, $transformer->partition(3)->toArray());
        self::assertEquals($expected, $transformer->partition(3)->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $partitioned = t\partition(3, range(1, 9));
        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }

    public function test_applies_to_iterators()
    {
        $partitioned = t\partition(3, new \ArrayIterator(range(1, 9)));
        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }
}
