<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer;

if (!function_exists(__NAMESPACE__.'\transform')) {

    /**
     * Prepares a transformation for a collection of items
     *
     * @param array|\Traversable $iterable
     * @param Composition $composition
     * @param Termination $termination
     * @param mixed $initial
     *
     * @return Transformer
     */
    function transform($iterable, Composition $composition = null, Termination $termination = null, $initial = null)
    {
        return (new Transformer($iterable, $composition, $termination, $initial));
    }

    /**
     * Alias for transform()
     *
     * @see transform()
     *
     * @param mixed $iterable
     * @param null|mixed $initial
     */
    function from($iterable, Composition $composition = null, Termination $termination = null, $initial = null)
    {
        return transform($iterable, $composition, $termination, $initial);
    }

    /**
     * Creates a composition of functions
     *
     * @param \callable[] ...$callbacks
     *
     * @return Composition
     */
    function compose(callable ...$callbacks)
    {
        return new Composition(...$callbacks);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    function identity($value)
    {
        return $value;
    }

    /**
     * Transforms a function into a pure function
     *
     * @param callable $function
     * @param array ...$arguments
     *
     * @return mixed
     */
    function curry(callable $function, ...$arguments)
    {
        return (new Curry($function))(...$arguments);
    }

    /**
     * Creates a comparison function for a callable criterion
     * The created function can be used with usort(), uasot() and uksort()
     *
     * @param callable $callback
     *
     * @return \Closure
     */
    function comparator(callable $callback)
    {
        return static function ($first, $second) use ($callback) {
            $first = ($callback)($first);
            $second = ($callback)($second);
            if ($first == $second) {
                return 0;
            }

            return $first < $second ? -1 : 1;
        };
    }

}