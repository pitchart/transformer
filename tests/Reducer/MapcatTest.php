<?php

namespace Pitchart\Transformer\Tests\Reducer;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Transducer as t;
use function Pitchart\Transformer\Tests\Fixtures\square;
use Pitchart\Transformer\Transformer;

class MapcatTest extends TestCase
{
    public function test_is_a_reducer()
    {
        self::assertInstanceOf(Reducer::class, t\mapcat(square())(t\to_array()));
    }

    public function test_can_map_items_of_a_list_and_concatenate_the_result()
    {
        $mapcat = (new Transformer([[0 ,1], [], [2, 3], [4, 5], [6]]))
            ->mapcat(function ($item) {
                return array_sum($item);
            })->toArray();

        self::assertEquals([1, 0, 5, 9, 6], $mapcat);
    }

    public function test_is_immutable()
    {
        $transformer = (new Transformer([[0 ,1], [], [2, 3], [4, 5], [6]]));
        $copy = clone $transformer;
        $function = function ($item) {
            return array_sum($item);
        };

        self::assertEquals([1, 0, 5, 9, 6], $transformer->mapcat($function)->toArray());
        self::assertEquals([1, 0, 5, 9, 6], $transformer->mapcat($function)->toArray());
        self::assertEquals($transformer, $copy);
    }

    public function test_applies_to_arrays()
    {
        $mapcat = t\mapcat(function ($item) {
                return array_sum($item);
            }, [[0 ,1], [], [2, 3], [4, 5], [6]]);

        self::assertEquals([1, 0, 5, 9, 6], $mapcat);
    }

    public function test_applies_to_iterators()
    {
        $mapcat = t\mapcat(function ($item) {
            return array_sum($item);
        }, new \ArrayIterator([[0 ,1], [], [2, 3], [4, 5], [6]]));

        self::assertEquals([1, 0, 5, 9, 6], $mapcat);
    }
}
