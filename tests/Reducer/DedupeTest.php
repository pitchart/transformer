<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Dedupe;
use Pitchart\Transformer\Transducer as t;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;

class DedupeTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\dedupe()(t\to_array()));
    }

    public function test_removes_consecutive_equals_items()
    {
        $deduped = (new Transformer([1, 2, 3, 2, 2, 4, 6, 5, 1, 0, 0, 1]))
            ->dedupe()->toArray();

        self::assertEquals([1, 2, 3, 2, 4, 6, 5, 1, 0, 1], $deduped);
    }

    public function test_applies_to_arrays()
    {
        $deduped = t\dedupe([1, 2, 3, 2, 2, 4, 6, 5, 1, 0, 0, 1]);
        self::assertEquals([1, 2, 3, 2, 4, 6, 5, 1, 0, 1], $deduped);
    }

    public function test_applies_to_iterators()
    {
        $deduped = t\dedupe(new \ArrayIterator([1, 2, 3, 2, 2, 4, 6, 5, 1, 0, 0, 1]));
        self::assertEquals([1, 2, 3, 2, 4, 6, 5, 1, 0, 1], $deduped);
    }

}
