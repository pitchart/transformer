<?php

namespace Pitchart\Transformer\Tests\Reducer\Termination;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Termination\Has;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\Tests\Fixtures\plus_one;
use Pitchart\Transformer\Transformer;

class HasTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $has = new Has(function ($item) { return $item != 0; });
        self::assertInstanceOf(Reducer::class, $has);
    }

    public function test_is_a_termination()
    {
        $has = new Has(function ($item) { return $item != 0; });
        self::assertInstanceOf(Termination::class, $has);
    }

    public function test_returns_true_if_collection_contains_matching_element()
    {
        $has = (new Transformer([1, 2, 3, 4]))
            ->has(function ($item) { return $item != 0; });

        self::assertTrue($has);
    }

    public function test_returns_false_if_collection_does_not_contain_matching_element()
    {
        $has = (new Transformer([1, 2, 3, 4]))
            ->has(function ($item) { return $item == 0; });

        self::assertFalse($has);
    }

    /**
     * @param callable $callback
     * @param bool $expected
     *
     * @dataProvider callableProvider
     */
    public function test_applies_after_operation(callable $callback, bool $expected)
    {
        $has = (new Transformer([1, 2, 3, 4]))
            ->map(plus_one())
            ->has($callback);
        self::assertEquals($expected, $has);
    }

    public function callableProvider()
    {
        yield from [
            [function ($item) { return $item == 5; }, true],
            [function ($item) { return $item == 1; }, false],
        ];
    }
}
