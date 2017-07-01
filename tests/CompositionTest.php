<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 01/07/17
 * Time: 15:08
 */

namespace Pitchart\Transformer\Tests;

use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Composition;

class CompositionTest extends TestCase
{

    public function test_composition_of_basic_functions() {
        $adds_one_and_returns_square = new Composition(plus_one(), square());

        $this->assertEquals(10, $adds_one_and_returns_square(3));
    }

    public function test_can_compose_itself() {
        $adds_one_and_two_and_returns_square = new Composition(square(), new Composition(plus_two(), plus_one()));

        $this->assertEquals(16, $adds_one_and_two_and_returns_square(1));
    }
}
