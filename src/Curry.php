<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

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

        return static function (...$args) use ($function, $arguments) {
            return (static function ($arguments) use ($function) {
                if (count($arguments) < (new \ReflectionFunction($function))->getNumberOfRequiredParameters()) {
                    return new self($function, ...$arguments);
                }

                return $function(...$arguments);
            })(array_merge($arguments, $args));
        };
    }
}
