<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\GroupBy;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

class GroupByTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\group_by(function ($item) {return $item['group'];})(t\to_single()));
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider groupableDataProvider
     */
    public function test_groups_items_by_a_callback($data, $callable, $expected)
    {
        $grouped = (new Transformer($data))
            ->groupBy($callable)
            ->single()
        ;

        self::assertEquals($expected, $grouped);
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider groupableDataProvider
     */
    public function test_is_immutable($data, $callable, $expected)
    {
        $transformer = (new Transformer($data));
        $copy = clone $transformer;

        self::assertEquals($expected, $transformer->groupBy($callable)->single());
        self::assertEquals($expected, $transformer->groupBy($callable)->single());
        self::assertEquals($transformer, $copy);
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider groupableDataProvider
     */
    public function test_applies_to_arrays($data, $callable, $expected)
    {
        $grouped = t\group_by($callable, $data);
        self::assertEquals($expected, $grouped);
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider groupableDataProvider
     */
    public function test_applies_to_iterators($data, $callable, $expected)
    {
        $grouped = t\group_by($callable, new \ArrayIterator($data));
        self::assertEquals($expected, $grouped);
    }

    public function groupableDataProvider()
    {
        return [
            [
                [
                    ['group' => 'foo', 'value' => 'bar'],
                    ['group' => 'bar', 'value' => 'foo'],
                    ['group' => 'fizz', 'value' => 'bar'],
                    ['group' => 'foo', 'value' => 'test'],
                    ['group' => 'fizz', 'value' => 'foo'],
                    ['group' => 'bar', 'value' => 'foo'],
                ],
                function ($item) {return $item['group'];},
                [
                    'foo' => [['group' => 'foo', 'value' => 'bar'], ['group' => 'foo', 'value' => 'test'],],
                    'bar' => [['group' => 'bar', 'value' => 'foo'], ['group' => 'bar', 'value' => 'foo'],],
                    'fizz' => [['group' => 'fizz', 'value' => 'bar'], ['group' => 'fizz', 'value' => 'foo'],],
                ]
            ]
        ];
    }
}
