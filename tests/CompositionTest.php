<?php

namespace Pitchart\Transformer\Tests;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Composition;

/**
 * Class CompositionTest
 *
 * @package Pitchart\Transformer\Tests
 *
 * @author Julien VITTE <vitte.julien@gmail.fr>
 */
class CompositionTest extends TestCase
{

    public function test_can_compose_one_function() {
        $adds_one = new Composition(plus_one());

        self::assertEquals(4, $adds_one(3));
    }

    public function test_can_compose_basic_functions() {
        $adds_one_and_returns_square = new Composition(plus_one(), square());

        self::assertEquals(10, $adds_one_and_returns_square(3));
    }

    public function test_can_compose_itself() {
        $adds_one_and_two_and_returns_square = new Composition(square(), new Composition(plus_two(), plus_one()));

        self::assertEquals(16, $adds_one_and_two_and_returns_square(1));
    }

    public function test_can_append_functions()
    {
        $adds_one_and_returns_square = (new Composition)->append(plus_one())->append(square());

        self::assertEquals(10, $adds_one_and_returns_square(3));
    }

}