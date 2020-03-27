<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer\Merge;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use Pitchart\Transformer\Transducer as t;

class MergeTest extends TestCase
{
    public function test_merges_collections()
    {
        $transformer = (new Transformer([1, 2, 3, 4, 5]));

        self::assertEquals([1, 2, 3, 4, 5, 4, 3, 2, 1], $transformer->merge([4, 3, 2, 1])->toArray());
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4, 5]));
        $copy = clone $transformer;

        self::assertEquals([1, 2, 3, 4, 5, 4, 3, 2, 1], $transformer->merge([4, 3, 2, 1])->toArray());
        self::assertEquals([1, 2, 3, 4, 5, 4, 3, 2, 1], $transformer->merge([4, 3, 2, 1])->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_on_arrays()
    {
        $merged = t\merge([1, 2, 3], [4, 5]);
        self::assertEquals([1, 2, 3, 4, 5], $merged);
    }

    public function test_applies_on_iterator()
    {
        $merged = t\merge(new \ArrayIterator([1, 2, 3]), new \ArrayIterator([4, 5]));
        self::assertEquals([1, 2, 3, 4, 5], $merged);
    }
}
