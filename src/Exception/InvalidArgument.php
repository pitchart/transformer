<?php

namespace Pitchart\Transformer\Exception;

class InvalidArgument extends \InvalidArgumentException
{

    public static function assertIterable($iterable, $classname, $method, $position)
    {
        if (!is_array($iterable)
            && !($iterable instanceof \Traversable)
        ) {
            throw new static(sprintf(
                '%s::%s() expects parameter %d to be iterable',
                $classname,
                $method,
                $position
            ));
        }
    }
}
