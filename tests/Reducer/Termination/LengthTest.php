<?php

namespace Pitchart\Transformer\Tests\Reducer\Termination;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Termination\Length;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\transform;

class LengthTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $length = new Length;
        self::assertInstanceOf(Reducer::class, $length);
    }

    public function test_is_a_termination()
    {
        $length = new Length;
        self::assertInstanceOf(Termination::class, $length);
    }
    
    public function test_is_zero_if_collection_is_empty()
    {
        $length = transform([])->length();
        
        $this->assertEquals(0, $length);
    }

    public function test_returns_collection_size_for_not_empty_collections()
    {
        $length = transform([1, 2, 3, 4])->length();

        $this->assertEquals(4, $length);
    }
}
