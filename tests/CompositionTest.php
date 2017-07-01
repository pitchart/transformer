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
        $composition = new Composition(plus_one(), square());

        $this->assertEquals(10, $composition(3));
    }
}
