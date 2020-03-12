<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer\Termination;

use Pitchart\Transformer\Exception\InvalidArgument;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\identity;

class Operation implements Termination
{
    /**
     * @var array
     */
    private static $operators;

    /**
     * @var string
     */
    private $operation;

    /**
     * Operation constructor.
     */
    public function __construct(string $operation)
    {
        if (self::$operators === null) {
            self::$operators = [
                '+' => static function ($result, $current) {
                    return $result+$current;
                },
                '-' => static function ($result, $current) {
                    return $result-$current;
                },
                '*' => static function ($result, $current) {
                    return $result*$current;
                },
                '/' => static function ($result, $current) {
                    return $result/$current;
                },
                '.' => static function ($result, $current) {
                    return $result.$current;
                },
            ];
        }

        if (!array_key_exists($operation, self::$operators)) {
            throw new InvalidArgument(sprintf('Invalid argument %s for %s constructor', $operation, static::class));
        }

        $this->operation = $operation;
    }

    public function init()
    {
        switch ($this->operation) {
            case '+':
            case '-':
                return 0;
            case '*':
            case '/':
                return 1;
        }

        return null;
    }

    public function step($result, $current)
    {
        return (self::$operators[$this->operation])($result, $current);
    }

    public function complete($result)
    {
        return $result;
    }
}
