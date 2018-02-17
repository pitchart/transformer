<?php

namespace Pitchart\Transformer;


class Curry
{
    /**
     * @var callable
     */
    private $function;

    /**
     * Curry constructor.
     *
     * @param callable $function
     */
    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    /**
     * @return \Closure
     */
    public function __invoke(...$arguments)
    {
        $function = $this->function;
        return function (...$args) use ($function, $arguments) {
            return (function ($arguments) use ($function) {
                if (count($arguments) < (new \ReflectionFunction($function))->getNumberOfRequiredParameters()) {
                    return new self($function, ...$arguments);
                }
                return $function(...$arguments);
            })(array_merge($arguments, $args));
        };
    }

}