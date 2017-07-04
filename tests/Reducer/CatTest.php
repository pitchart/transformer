<?php

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer\Cat;
use Pitchart\Transformer\Transformer;

class CatTest extends TestCase
{
    public function test_concatenates_items_from_nested_lists()
    {
        $concatenated = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->cat()->toArray();

        self::assertEquals([0, 1, 2, 3, [4, 5], 6], $concatenated);
    }

    public function test_removes_empty_nested_lists()
    {
        $concatenated = (new Transformer([0 ,1, [], [2, 3], [[4, 5], 6]]))
            ->cat()->toArray();

        self::assertEquals([0, 1, 2, 3, [4, 5], 6], $concatenated);
    }
}
