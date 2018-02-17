<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer\Flatten;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;

class FlattenTest extends TestCase
{
    public function test_converts_nested_sequences_to_a_single_flat_sequence()
    {
        $flat = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->flatten()->toArray();

        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flat);
    }

    public function test_flatten_is_immutable()
    {
        $flatten = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->flatten();

        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flatten->toArray());
        self::assertEquals([0, 1, 2, 3, 4, 5, 6], $flatten->toArray());
    }
}
