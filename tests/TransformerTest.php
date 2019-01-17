<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Tests;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Distinct;
use Pitchart\Transformer\Tests\Fixtures as f;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\transform;

/**
 * @internal
 */
final class TransformerTest extends TestCase
{
    public function test_transform_helper_instantiate_a_transformer()
    {
        $transformation = transform([1,2,3,4]);
        self::assertInstanceOf(Transformer::class, $transformation);
    }

    /**
     * @param iterable $iterable
     * @param mixed $expected
     * @dataProvider iterableProvider
     */
    public function test_can_transform_iterables($iterable, $expected)
    {
        $mapped = (new Transformer($iterable))
            ->map(f\plus_one())->toArray();

        self::assertEquals($expected, $mapped);
    }

    public function test_can_transduce_into_iterator()
    {
        $result = (new Transformer(range(1, 6)))
            ->toIterator();

        self::assertInstanceOf(\Iterator::class, $result);

        $values = [];
        foreach ($result as $res) {
            $values[] = $res;
        }

        self::assertEquals([1, 2, 3, 4, 5, 6], $values);
    }

    public function test_can_transduce_into_string()
    {
        $result = (new Transformer(range(1, 6)))->toString('');

        self::assertEquals('123456', $result);
    }

    public function test_can_map_items_of_iterable()
    {
        $mapped = (new Transformer(range(1, 6)))
            ->map(f\plus_one())->toArray();

        self::assertEquals([2,3,4,5,6,7], $mapped);
    }

    public function test_can_filter_items_of_iterable()
    {
        $filtered = (new Transformer(range(1, 6)))
            ->filter(f\is_even())->toArray();

        self::assertEquals([2,4,6], $filtered);
    }

    public function test_can_keep_items_for_which_a_callable_does_not_return_null()
    {
        $kept = (new Transformer([0, 1, null, true, false]))
            ->keep(function ($item) {
                return $item;
            })->toArray();

        self::assertEquals([0, 1, true, false], $kept);
    }

    public function test_can_reject_items_of_iterable()
    {
        $rejected = (new Transformer(range(1, 6)))
            ->reject(f\is_even())->toArray();

        self::assertEquals([1,3,5], $rejected);
    }

    public function test_can_get_first_matching_item_of_iterable()
    {
        $first = (new Transformer(range(1, 6)))
            ->first(f\is_greater_than_three())->single();

        self::assertEquals(4, $first);
    }

    public function test_can_concatenate_items_of_nested_list()
    {
        $concatenated = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->cat()->toArray();

        self::assertEquals([0, 1, 2, 3, [4, 5], 6], $concatenated);
    }

    public function test_can_map_items_of_a_list_and_concatenate_the_result()
    {
        $mapcat = (new Transformer([[0 ,1], [], [2, 3], [4, 5], [6]]))->mapcat(function ($item) {
            return array_sum($item);
        })->toArray();

        self::assertEquals([1, 0, 5, 9, 6], $mapcat);
    }

    public function test_can_extract_a_number_of_values_from_a_collection()
    {
        $extracted = (new Transformer(range(1, 6)))
            ->take(3)->toArray();

        self::assertEquals([1,2,3], $extracted);
    }

    public function test_can_extract_as_long_as_callback_is_valid()
    {
        $extracted = (new Transformer(range(1, 6)))
            ->takeWhile(f\is_lower_than_four())->toArray();

        self::assertEquals([1,2,3], $extracted);
    }

    public function test_can_extract_every_nth_items_of_a_collection()
    {
        $extracted = (new Transformer(range(1, 9)))
            ->takeNth(3)->toArray();

        self::assertEquals([3, 6 , 9], $extracted);
    }

    public function test_can_drop_a_number_of_values_from_a_collection()
    {
        $dropped = (new Transformer(range(1, 6)))
            ->drop(4)->toArray();

        self::assertEquals([5,6], $dropped);
    }

    public function test_can_drop_items_while_a_predicat_is_true()
    {
        $dropped = (new Transformer(range(1, 6)))
            ->dropWhile(f\is_lower_than_four())->toArray();

        self::assertEquals([4, 5, 6], $dropped);
    }

    public function test_can_paginate_over_items()
    {
        $paginated = (new Transformer(range(1, 6)))
            ->paginate(2, 2)
            ->toArray();

        self::assertEquals([3, 4], $paginated);
    }

    public function test_can_remove_duplicated_items_in_collection()
    {
        $distincts = (new Transformer([1, 2, 3, 2, 4, 6, 5, 1, 0]))
            ->distinct()->toArray();

        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts);
    }

    public function test_can_remove_consecutive_equals_items()
    {
        $deduped = (new Transformer([1, 2, 3, 2, 2, 4, 6, 5, 1, 0, 0, 1]))
            ->dedupe()->toArray();

        self::assertEquals([1, 2, 3, 2, 4, 6, 5, 1, 0, 1], $deduped);
    }

    public function test_can_partition_a_collection()
    {
        $partitioned = (new Transformer(range(1, 9)))
            ->partition(3)->toArray();

        self::assertEquals([[1, 2, 3], [4, 5, 6], [7, 8, 9]], $partitioned);
    }

    public function test_can_partition_a_collection_by_a_callback()
    {
        $partitioned = (new Transformer([0, 2, 3, 1, 4, 5, 6, 8, 9, 7]))
            ->partitionBy(f\is_even())->toArray();

        self::assertEquals([[0, 2], [3, 1], [4], [5], [6,8], [9, 7]], $partitioned);
    }

    public function test_can_group_items_by_a_callback()
    {
        $grouped = (new Transformer([
            ['group' => 'foo', 'value' => 'bar'],
            ['group' => 'bar', 'value' => 'foo'],
            ['group' => 'fizz', 'value' => 'bar'],
            ['group' => 'foo', 'value' => 'test'],
            ['group' => 'fizz', 'value' => 'foo'],
            ['group' => 'bar', 'value' => 'foo'],
        ]))
            ->groupBy(function ($item) {
                return $item['group'];
            })
            ->single()
        ;

        self::assertEquals([
            'foo' => [['group' => 'foo', 'value' => 'bar'], ['group' => 'foo', 'value' => 'test'],],
            'bar' => [['group' => 'bar', 'value' => 'foo'], ['group' => 'bar', 'value' => 'foo'],],
            'fizz' => [['group' => 'fizz', 'value' => 'bar'], ['group' => 'fizz', 'value' => 'foo'],],
        ], $grouped);
    }

    public function test_can_replace_items_in_a_collection()
    {
        $replaced = (new Transformer(range(1, 6)))
            ->replace([3 => 'Fizz', 5 => 'Buzz'])->toArray();

        self::assertEquals([1, 2, 'Fizz', 4, 'Buzz', 6], $replaced);
    }

    public function test_can_compose_transformations()
    {
        $first = (new Transformer(range(1, 6)))
            ->map(f\plus_one())
            ->filter(f\is_even())
            ->first(f\is_greater_than_three())
            ->single();

        self::assertEquals(4, $first);
    }

    public function test_can_reduce()
    {
        $values = (new Transformer([1,2,2,3,4,5]))->reduce(function(Reducer $reducer){
            return new Distinct($reducer);
        })->toArray();

        self::assertEquals([1,2,3,4,5], $values);
    }

    public function iterableProvider()
    {
        return [
            'array' => [range(1, 6), range(2, 7)],
            'iterator' => [new \ArrayIterator(range(1, 6)), range(2, 7)],
            'array access' => [new \ArrayObject(range(1, 6)), range(2, 7)],
        ];
    }
}
