<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Tests;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Composition;
use Pitchart\Transformer\Tests\Fixtures as f;

/**
 * Class CompositionTest
 *
 * @package Pitchart\Transformer\Tests
 *
 * @author Julien VITTE <vitte.julien@gmail.fr>
 *
 * @internal
 */
final class CompositionTest extends TestCase
{
    public function test_null_composition_returns_passed_argument()
    {
        $null = new Composition();
        self::assertEquals(2, $null(2));
    }

    public function test_can_compose_one_function()
    {
        $adds_one = new Composition(f\plus_one());

        self::assertEquals(4, $adds_one(3));
    }

    public function test_can_compose_basic_functions()
    {
        $adds_one_and_returns_square = new Composition(f\plus_one(), f\square());

        self::assertEquals(10, $adds_one_and_returns_square(3));
    }

    public function test_can_compose_itself()
    {
        $adds_one_and_two_and_returns_square = new Composition(f\square(), new Composition(f\plus_two(), f\plus_one()));

        self::assertEquals(16, $adds_one_and_two_and_returns_square(1));
    }

    public function test_can_append_functions()
    {
        $adds_one_and_returns_square = (new Composition)->append(f\plus_one())->append(f\square());

        self::assertEquals(10, $adds_one_and_returns_square(3));
    }
}
