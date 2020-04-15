<?php

namespace Pitchart\Transformer\Tests\Reducer\Termination;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Termination\ToResource;
use PHPUnit\Framework\TestCase;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\transform;

class ToResourceTest extends TestCase
{
    public function test_is_a_reducer()
    {
        $resource = new ToResource(function ($item) { return (string) $item; });
        self::assertInstanceOf(Reducer::class, $resource);
    }

    public function test_is_a_termination()
    {
        $resource = new ToResource(function ($item) { return (string) $item; });
        self::assertInstanceOf(Termination::class, $resource);
    }
    
    public function test_initializes_with_a_resource()
    {
        $resource = new ToResource(function ($item) { return (string) $item; });
        $init = $resource->init();

        self::assertInternalType('resource', $init);
    }

    public function test_writes_content_on_resource()
    {
        $resource = transform([1, 2, 3, 4])->toResource();
        rewind($resource);

        self::assertEquals('1234', stream_get_contents($resource));
    }

    public function test_writes_content_on_resource_with_callback()
    {
        $resource = transform([1, 2, 3, 4])->toResource(function ($item) {
            return sprintf("%s\n", $item);
        });
        rewind($resource);

        self::assertEquals("1\n2\n3\n4\n", stream_get_contents($resource));
    }
}
