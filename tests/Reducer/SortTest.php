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
use Pitchart\Transformer\Reducer\Sort;
use Pitchart\Transformer\Transducer as t;
use Pitchart\Transformer\Transformer;

/**
 * @internal
 */
final class SortTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\sort(function ($item) {
            return $item['group'];
        })(t\to_array()));
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
            ->sort($callable)
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

        self::assertEquals($expected, $transformer->sort($callable)->toArray());
        self::assertEquals($expected, $transformer->sort($callable)->toArray());
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
        $grouped = t\sort($callable, $data);
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
        $grouped = t\sort($callable, new \ArrayIterator($data));
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
                function ($a, $b) {
                    $aKey = sprintf('%s.%s', $a['group'], $a['value']);
                    $bKey = sprintf('%s.%s', $b['group'], $b['value']);
                    if ($aKey == $bKey) {
                        return 0;
                    }

                    return $aKey < $bKey ? -1 : 1;
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
