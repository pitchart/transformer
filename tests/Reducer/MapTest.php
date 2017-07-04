<?php

namespace Pitchart\Transformer\Tests\Reducer;

use Pitchart\Transformer\Reducer\Map;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Transformer;
use function Pitchart\Transformer\Tests\square;

class MapTest extends TestCase
{

    /**
     * @test
     */
    public function applies_a_callable_on_each_item()
    {
        $squared = (new Transformer(range(1, 2)))
            ->map(square())
            ->toArray();

        $this->assertEquals([1, 4], $squared);
    }

}
