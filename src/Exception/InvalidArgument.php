<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Exception;

class InvalidArgument extends \InvalidArgumentException
{
    public static function assertIterable($iterable, $method, $position, $classname = null)
    {
        if (!is_array($iterable)
            && !($iterable instanceof \Traversable)
        ) {
            if ($classname === null) {
                $message = sprintf(
                    'function %s() expects parameter %d to be iterable',
                    $method,
                    $position
                );
            } else {
                $message = sprintf(
                    '%s::%s() expects parameter %d to be iterable',
                    $classname,
                    $method,
                    $position
                );
            }

            throw new static($message);
        }
    }
}
