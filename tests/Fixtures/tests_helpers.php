<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Tests\Fixtures;

function plus_one()
{
    return function ($number) {
        return $number + 1;
    };
}

function plus_two()
{
    return function ($number) {
        return $number + 2;
    };
}

function square()
{
    return function ($number) {
        return pow($number, 2);
    };
}

function is_even()
{
    return function ($number) {
        return $number % 2 == 0;
    };
}

function is_greater_than_three()
{
    return function ($number) {
        return $number > 3;
    };
}

function is_lower_than_four()
{
    return function ($number) {
        return $number < 4;
    };
}
