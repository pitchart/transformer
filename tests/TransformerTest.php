<?php

namespace Pitchart\Transformer\Tests;

use Pitchart\Transformer\Transformer;
use PHPUnit\Framework\TestCase;
use function Pitchart\Transformer\transform;
use Pitchart\Transformer\Tests as t;

class TransformerTest extends TestCase
{
    public function test_transform_helper_instantiate_a_transformer()
    {
        $transformation = transform([1,2,3,4]);
        self::assertInstanceOf(Transformer::class, $transformation);
    }

    public function test_can_map_items_of_iterable()
    {
        $mapped = (new Transformer(range(1,6)))
            ->map(t\plus_one())->toArray();

        self::assertEquals([2,3,4,5,6,7], $mapped);
    }

    public function test_can_filter_items_of_iterable()
    {
        $filtered = (new Transformer(range(1,6)))
            ->filter(t\is_even())->toArray();

        self::assertEquals([2,4,6], $filtered);
    }

    public function test_can_reject_items_of_iterable()
    {
        $rejected = (new Transformer(range(1,6)))
            ->reject(t\is_even())->toArray();

        self::assertEquals([1,3,5], $rejected);
    }

    public function test_can_get_first_matching_item_of_iterable()
    {
        $first = (new Transformer(range(1,6)))
            ->first(t\is_greater_than_three())->single();

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
            ->takeWhile(t\is_lower_than_four())->toArray();

        self::assertEquals([1,2,3], $extracted);
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
            ->dropWhile(t\is_lower_than_four())->toArray();

        self::assertEquals([4, 5, 6], $dropped);
    }

    public function test_can_remove_duplicated_items_in_collection()
    {
        $distincts = (new Transformer([1, 2, 3, 2, 4, 6, 5, 1, 0]))
            ->distinct()->toArray();

        self::assertEquals([1, 2, 3, 4, 6, 5, 0], $distincts);
    }



    public function test_can_compose_transformations()
    {
        $first = (new Transformer(range(1,6)))
            ->map(t\plus_one())
            ->filter(t\is_even())
            ->first(t\is_greater_than_three())
            ->single();

        self::assertEquals(4, $first);
    }

}
