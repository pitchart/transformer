<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Distinct;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

class DistinctTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $distinct = t\distinct();
        self::assertInstanceOf(Reducer::class, $distinct(t\to_array()));
    }

    public function test_removes_duplicated_values()
    {
        $distincts = t\transduce(t\distinct(), t\to_array(), [1, 2, 3, 2, 4, 6, 5, 1, 0]);
        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts);
    }

    public function test_removes_duplicated_values_with_initial_values()
    {
        $distincts = t\transduce(t\distinct(), t\to_array(), [1, 2, 3, 2, 4, 6, 5, 1, 0], [7, 2]);
        self::assertEquals([7, 2, 1, 2, 3, 4, 6, 5, 0], $distincts);
    }

    public function test_successive_transformations_give_equal_results()
    {
        $distincts = (new Transformer([1, 2, 3, 2, 4, 6, 5, 1, 0]))
            ->distinct();

        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts->toArray());
        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts->toArray());
    }

}