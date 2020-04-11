<?php

namespace Pitchart\Transformer\Tests\Reducer\Termination;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Termination\ToCsv;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\transform;

class ToCsvTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $resource = new ToCsv();
        self::assertInstanceOf(Reducer::class, $resource);
    }

    public function test_is_a_termination()
    {
        $resource = new ToCsv();
        self::assertInstanceOf(Termination::class, $resource);
    }

    public function test_initializes_with_a_resource()
    {
        $resource = new ToCsv();
        $init = $resource->init();

        self::assertInternalType('resource', $init);
    }

    public function test_writes_array_content_on_resource()
    {
        $resource = transform([[1, 2, 3, 4]])->toCsv();
        rewind($resource);

        self::assertEquals("1,2,3,4\n", stream_get_contents($resource));
    }

    public function test_writes_iterable_content_on_resource()
    {
        $resource = transform([new \ArrayIterator([1, 2, 3, 4])])->toCsv();
        rewind($resource);

        self::assertEquals("1,2,3,4\n", stream_get_contents($resource));
    }

    public function test_writes_flat_content_on_resource()
    {
        $resource = transform([1, 2, 3, 4])->toCsv();
        rewind($resource);

        self::assertEquals("1\n2\n3\n4\n", stream_get_contents($resource));
    }

}
