<?php

namespace Pitchart\Transformer\Transducer;

use Pitchart\Transformer\Reducer;

/**
 * Creates a transducer function for mapping
 *
 * @param callable $callback
 *
 * @return \Closure
 */
function map(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\Map($reducer, $callback);
    };
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function filter(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\Filter($reducer, $callback);
    };
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function first(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\First($reducer, $callback);
    };
}

/**
 * @return Reducer\Termination\SingleResult
 */
function to_single()
{
    return new Reducer\Termination\SingleResult();
}

/**
 * @return Reducer\Termination\ToArray
 */
function to_array()
{
    return new Reducer\Termination\ToArray();
}