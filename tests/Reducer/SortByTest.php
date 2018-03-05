<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\SortBy;
use Pitchart\Transformer\Transducer as t;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;

class SortByTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\sort_by(function ($item) {return $item['group'];})(t\to_array()));
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider sortableDataProvider
     */
    public function test_orders_items_by_a_callback($data, $callable, $expected)
    {
        $grouped = (new Transformer($data))
            ->sortBy($callable)
            ->toArray()
        ;

        self::assertEquals($expected, $grouped);
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider sortableDataProvider
     */
    public function test_is_immutable($data, $callable, $expected)
    {
        $transformer = (new Transformer($data));
        $copy = clone $transformer;

        self::assertEquals($expected, $transformer->sortBy($callable)->toArray());
        self::assertEquals($expected, $transformer->sortBy($callable)->toArray());
        self::assertEquals($transformer, $copy);
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider sortableDataProvider
     */
    public function test_applies_to_arrays($data, $callable, $expected)
    {
        $grouped = t\sort_by($callable, $data);
        self::assertEquals($expected, $grouped);
    }

    /**
     * @param array $data
     * @param \Closure $callable
     * @param array $expected
     *
     * @dataProvider sortableDataProvider
     */
    public function test_applies_to_iterators($data, $callable, $expected)
    {
        $grouped = t\sort_by($callable, new \ArrayIterator($data));
        self::assertEquals($expected, $grouped);
    }

    public function sortableDataProvider()
    {
        return [
            [
                [
                    ['group' => 'foo', 'value' => 'bar'],
                    ['group' => 'bar', 'value' => 'foo'],
                    ['group' => 'fizz', 'value' => 'bar'],
                    ['group' => 'foo', 'value' => 'test'],
                    ['group' => 'fizz', 'value' => 'foo'],
                    ['group' => 'bar', 'value' => 'fizz'],
                ],
                function ($item) {
                    return sprintf('%s.%s', $item['group'], $item['value']);
                },
                [
                    ['group' => 'bar', 'value' => 'fizz'],
                    ['group' => 'bar', 'value' => 'foo'],
                    ['group' => 'fizz', 'value' => 'bar'],
                    ['group' => 'fizz', 'value' => 'foo'],
                    ['group' => 'foo', 'value' => 'bar'],
                    ['group' => 'foo', 'value' => 'test'],
                ]
            ]
        ];
    }
}
