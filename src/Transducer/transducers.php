<?php

namespace Pitchart\Transformer\Transducer;

use function Pitchart\Transformer\compose;
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
function keep(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\Keep($reducer, $callback);
    };
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function remove(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\Filter($reducer, function ($item) use ($callback) {
            return !($callback($item));
        });
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
 * @return \Closure
 */
function cat()
{
    return function (Reducer $reducer) {
        return new Reducer\Cat($reducer);
    };
}

/**
 * @param callable $callback
 *
 * @return \Pitchart\Transformer\Composition
 */
function mapcat(callable $callback)
{
    return compose(map($callback), cat());
}

/**
 * @return \Closure
 */
function flatten()
{
    return function (Reducer $reducer) {
        return new Reducer\Flatten($reducer);
    };
}

/**
 * @param int $number
 *
 * @return \Closure
 */
function take(int $number)
{
    return function (Reducer $reducer) use ($number) {
        return new Reducer\Take($reducer, $number);
    };
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function take_while(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\TakeWhile($reducer, $callback);
    };
}

/**
 * @param int $frequency
 *
 * @return \Closure
 */
function take_nth(int $frequency)
{
    return function (Reducer $reducer) use ($frequency) {
        return new Reducer\TakeNth($reducer, $frequency);
    };
}

/**
 * @param int $number
 *
 * @return \Closure
 */
function drop(int $number)
{
    return function (Reducer $reducer) use ($number) {
        return new Reducer\Drop($reducer, $number);
    };
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function drop_while(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\DropWhile($reducer, $callback);
    };
}

function paginate($page = 1, $numberOfItems = 10)
{
    return function (Reducer $reducer) use ($page, $numberOfItems) {
        return new Reducer\Paginate($reducer, $page, $numberOfItems);
    };
}

/**
 * @param array $map
 *
 * @return \Closure
 */
function replace(array $map)
{
    return function (Reducer $reducer) use ($map) {
        return new Reducer\Replace($reducer, $map);
    };
}

/**
 * @return \Closure
 */
function distinct()
{
    return function (Reducer $reducer) {
        return new Reducer\Distinct($reducer);
    };
}

/**
 * @return \Closure
 */
function dedupe()
{
    return function (Reducer $reducer) {
        return new Reducer\Dedupe($reducer);
    };
}

/**
 * @param int $size
 *
 * @return \Closure
 */
function partition(int $size)
{
    return function (Reducer $reducer) use ($size) {
        return new Reducer\Partition($reducer, $size);
    };
}

// Terminations

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
