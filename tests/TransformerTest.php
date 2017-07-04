<?php

namespace Pitchart\Transformer\Tests;

use Pitchart\Transformer\Transformer;
use PHPUnit\Framework\TestCase;
use function Pitchart\Transformer\transform;

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
            ->map(plus_one())->toArray();

        self::assertEquals([2,3,4,5,6,7], $mapped);
    }

    public function test_can_filter_items_of_iterable()
    {
        $filtered = (new Transformer(range(1,6)))
            ->filter(is_even())->toArray();

        self::assertEquals([2,4,6], $filtered);
    }

    public function test_can_reject_items_of_iterable()
    {
        $rejected = (new Transformer(range(1,6)))
            ->reject(is_even())->toArray();

        self::assertEquals([1,3,5], $rejected);
    }

    public function test_can_get_first_matching_item_of_iterable()
    {
        $first = (new Transformer(range(1,6)))
            ->first(is_greater_than_three())->single();

        self::assertEquals(4, $first);
    }

    public function test_can_concatenate_items_of_nested_list()
    {
        $concatenated = (new Transformer([0 ,1, [2, 3], [[4, 5], 6]]))
            ->cat()->toArray();

        self::assertEquals([0, 1, 2, 3, [4, 5], 6], $concatenated);
    }

    public function test_can_compose_transformations()
    {
        $first = (new Transformer(range(1,6)))
            ->map(plus_one())
            ->filter(is_even())
            ->first(is_greater_than_three())->single();

        self::assertEquals(4, $first);
    }

}
