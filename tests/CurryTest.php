<?php

namespace Pitchart\Transformer\Tests;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Curry;
use Pitchart\Transformer as t;

class CurryTest extends TestCase
{
    public function test_object_curries_closures()
    {
        $plus = function ($x, $y) { return $x + $y; };
        $curriedPlus = new Curry($plus);
        $plusOne = $curriedPlus(1);

        self::assertEquals($plus(1, 1), $plusOne(1));
        self::assertEquals($plus(1, 1), $curriedPlus(1)(1));
    }

    public function test_object_curries_php_functions()
    {
        $curried = new Curry('implode');
        self::assertEquals('abc', $curried('')(['a', 'b', 'c']));
    }

    public function test_helper_curries_closures()
    {
        $plus = function ($x, $y) { return $x + $y; };
        $plusOne = t\curry($plus, 1);

        self::assertEquals($plus(1, 1), $plusOne(1));
    }

    public function test_helper_curries_php_functions()
    {
        $curried = t\curry('implode', '');
        self::assertEquals('abc', $curried(['a', 'b', 'c']));
    }
}
