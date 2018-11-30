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
use Pitchart\Transformer\Reducer\Dedupe;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class DedupeTest extends TestCase
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

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 2, 2, 4, 6, 5, 1, 0, 0, 1]));
        $copy = clone $transformer;

        self::assertEquals([1, 2, 3, 2, 4, 6, 5, 1, 0, 1], $transformer->dedupe()->toArray());
        self::assertEquals([1, 2, 3, 2, 4, 6, 5, 1, 0, 1], $transformer->dedupe()->toArray());
        self::assertEquals($transformer, $copy);
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
