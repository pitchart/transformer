<?php

namespace Pitchart\Transformer\Tests\Reducer\Termination;

use Pitchart\Transformer\Exception\InvalidArgument;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Termination\Operation;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\Tests\Fixtures\plus_one;
use Pitchart\Transformer\Transformer;

class OperationTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $operation = new Operation('+');
        self::assertInstanceOf(Reducer::class, $operation);
    }

    public function test_is_a_termination()
    {
        $operation = new Operation('+');
        self::assertInstanceOf(Termination::class, $operation);
    }

    public function test_throws_an_exception_for_invalid_operators()
    {
        self::expectException(InvalidArgument::class);
        $operation = new Operation('a');
    }

    public function test_can_calculate_sum()
    {
        $sum = (new Transformer([1, 2, 3, 4]))
            ->map(plus_one())
            ->sum();

        self::assertEquals(14, $sum);
    }

    public function test_can_calculate_sum_with_initial_value()
    {
        $sum = (new Transformer([1, 2, 3, 4]))
            ->initialize(6)
            ->map(plus_one())
            ->sum();

        self::assertEquals(20, $sum);
    }

    public function test_can_calculate_difference()
    {
        $difference = (new Transformer([1, 2, 3, 4]))
            ->difference();

        self::assertEquals(-10, $difference);
    }

    public function test_can_calculate_difference_with_initial_value()
    {
        $difference = (new Transformer([1, 2, 3, 4]))
            ->initialize(20)
            ->difference();

        self::assertEquals(10, $difference);
    }

    public function test_can_calculate_multiplication()
    {
        $result = (new Transformer([1, 2, 3, 4]))
            ->multiply();

        self::assertEquals(24, $result);
    }

    public function test_can_calculate_multiplication_with_initial_value()
    {
        $result = (new Transformer([1, 2, 3, 4]))
            ->initialize(2)
            ->multiply();

        self::assertEquals(48, $result);
    }

    public function test_can_calculate_division()
    {
        $result = (new Transformer([1, 2, 3]))
            ->divide();

        self::assertEquals(0.16666666666666, $result);
    }

    public function test_can_calculate_division_with_initial_value()
    {
        $result = (new Transformer([1, 2, 3]))
            ->initialize(6)
            ->divide();

        self::assertEquals(1, $result);
    }

    public function test_can_concatenate_strings()
    {
        $concat = (new Transformer(['a', 'b', 'c', 'd']))
            ->concat();

        self::assertEquals('abcd', $concat);
    }

    public function test_can_concatenate_numerics()
    {
        $concat = (new Transformer(range(1, 6)))
            ->concat();

        self::assertEquals('123456', $concat);
    }

    public function test_can_concatenate_with_initial_value()
    {
        $concat = (new Transformer(range(1, 6)))
            ->initialize('abc')
            ->concat();

        self::assertEquals('abc123456', $concat);
    }

}
