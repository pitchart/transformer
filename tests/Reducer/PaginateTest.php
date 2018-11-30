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
use Pitchart\Transformer\Reducer\Paginate;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class PaginateTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\paginate()(t\to_array()));
    }

    public function test_paginates_over_items()
    {
        $paginated = (new Transformer(range(1, 6)))
            ->paginate(2, 2)
            ->toArray();
        self::assertEquals([3, 4], $paginated);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([1, 2, 3, 4, 5]));
        $copy = clone $transformer;

        self::assertEquals([3, 4], $transformer->paginate(2, 2)->toArray());
        self::assertEquals([3, 4], $transformer->paginate(2, 2)->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $paginated = t\paginate(2, 2, range(1, 6));
        self::assertEquals([3, 4], $paginated);
    }

    public function test_applies_to_iterators()
    {
        $paginated = t\paginate(2, 2, new \ArrayIterator(range(1, 6)));
        self::assertEquals([3, 4], $paginated);
    }
}
