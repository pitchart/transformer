<?php

namespace Pitchart\Transformer;

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
 * Creates a composition of functions
 *
 * @param \callable[] ...$callables
 *
 * @return Composition
 */
function compose(callable ...$callables)
{
    return new Composition(...$callables);
}
