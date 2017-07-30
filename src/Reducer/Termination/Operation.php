<?php

namespace Pitchart\Transformer\Reducer\Termination;


use Pitchart\Transformer\Exception\InvalidArgument;
use function Pitchart\Transformer\identity;
use Pitchart\Transformer\Termination;

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
                '+' => function($result, $current) { return $result+$current; },
                '-' => function($result, $current) { return $result-$current; },
                '*' => function($result, $current) { return $result*$current; },
                '/' => function($result, $current) { return $result/$current; },
                '.' => function($result, $current) { return $result.$current; },
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