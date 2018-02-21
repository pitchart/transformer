<?php

namespace Pitchart\Transformer\Transducer;

use function Pitchart\Transformer\compose;
use Pitchart\Transformer\Exception\InvalidArgument;
use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Termination;

/**
 * @param callable $transducer
 * @param Termination $reducer
 * @param $iterable
 * @param null $initial
 *
 * @return mixed
 */
function transduce(callable $transducer, Termination $reducer, $iterable, $initial = null)
{
    InvalidArgument::assertIterable($iterable, __FUNCTION__, 3);

    /** @var Reducer $transformation */
    $transformation = $transducer($reducer);

    $accumulator = ($initial === null) ? $transformation->init() : $initial;

    foreach ($iterable as $current) {
        $accumulator = $transformation->step($accumulator, $current);

        //early termination
        if ($accumulator instanceof Reduced) {
            $accumulator = $accumulator->value();
            break;
        }
    }

    return $transformation->complete($accumulator);
}

/**
 * Creates a transducer function for mapping
 *
 * @param callable $callback
 * @param iterable|null $sequence
 *
 * @return array|\Closure|mixed
 */
function map(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return function (Reducer $reducer) use ($callback) {
            return new Reducer\Map($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        return array_map($callback, $sequence);
    }
    return transduce(map($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param iterable|null $sequence
 *
 * @return array|\Closure
 */
function filter(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return function (Reducer $reducer) use ($callback) {
            return new Reducer\Filter($reducer, $callback);
        };
    }

    if (is_array($sequence)) {
        return array_values(array_filter($sequence, $callback));
    }
    return transduce(filter($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param iterable|null $sequence
 *
 * @return array|\Closure
 */
function keep(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return function (Reducer $reducer) use ($callback) {
            return new Reducer\Keep($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        return array_values(array_filter($sequence, function ($item) use ($callback) {
            return $callback($item) !== null;
        }));
    }
    return transduce(keep($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function remove(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return function (Reducer $reducer) use ($callback) {
            return new Reducer\Filter($reducer, function ($item) use ($callback) {
                return !($callback($item));
            });
        };
    }
    if (is_array($sequence)) {
        return array_values(array_filter($sequence, function ($item) use ($callback) {
            return !$callback($item);
        }));
    }
    return transduce(remove($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function first(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return function (Reducer $reducer) use ($callback) {
            return new Reducer\First($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        $filtered = filter($callback, $sequence);
        return array_shift($filtered);
    }
    return transduce(first($callback), to_single(), $sequence);
}

/**
 * @param iterable|null $sequence
 *
 * @return \Closure|array
 */
function cat($sequence = null)
{
    if ($sequence === null) {
        return function (Reducer $reducer) {
            return new Reducer\Cat($reducer);
        };
    }
    return transduce(cat(), to_array(), $sequence);
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

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function partition_by(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\PartitionBy($reducer, $callback);
    };
}

/**
 * @param callable $callback
 *
 * @return \Closure
 */
function group_by(callable $callback)
{
    return function (Reducer $reducer) use ($callback) {
        return new Reducer\GroupBy($reducer, $callback);
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
 * @return Reducer\Termination\ToString
 */
function to_string($glue = '')
{
    return new Reducer\Termination\ToString($glue);
}

/**
 * @return Reducer\Termination\ToArray
 */
function to_array()
{
    return new Reducer\Termination\ToArray();
}

/**
 * @return Reducer\Termination\ToIterator
 */
function to_iterator()
{
    return new Reducer\Termination\ToIterator();
}

/**
 * @param string $operator
 *
 * @return Reducer\Termination\Operation
 */
function to_operation(string $operator)
{
    return new Reducer\Termination\Operation($operator);
}