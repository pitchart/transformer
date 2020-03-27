<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Transducer;

use Pitchart\Transformer\Exception\InvalidArgument;
use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\comparator;
use function Pitchart\Transformer\compose;

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
 * @param null|iterable $sequence
 *
 * @return array|\Closure|mixed
 */
function map(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Map($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        return \array_map($callback, $sequence);
    }

    return transduce(map($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|iterable $sequence
 *
 * @return array|\Closure
 */
function filter(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Filter($reducer, $callback);
        };
    }

    if (is_array($sequence)) {
        return \array_values(\array_filter($sequence, $callback));
    }

    return transduce(filter($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|iterable $sequence
 *
 * @return array|\Closure
 */
function keep(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Keep($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        return \array_values(\array_filter($sequence, static function ($item) use ($callback) {
            return $callback($item) !== null;
        }));
    }

    return transduce(keep($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|iterable $sequence
 *
 * @return array|\Closure|mixed
 */
function remove(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Filter($reducer, static function ($item) use ($callback) {
                return !($callback($item));
            });
        };
    }
    if (is_array($sequence)) {
        return \array_values(\array_filter($sequence, static function ($item) use ($callback) {
            return !$callback($item);
        }));
    }

    return transduce(remove($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|iterable $sequence
 *
 * @return \Closure|mixed
 */
function first(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\First($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        $filtered = filter($callback, $sequence);

        return \array_shift($filtered);
    }

    return transduce(first($callback), to_single(), $sequence);
}

/**
 * @param null|iterable $sequence
 *
 * @return array|\Closure
 */
function cat($sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) {
            return new Reducer\Cat($reducer);
        };
    }

    return transduce(cat(), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|iterable $sequence
 *
 * @return mixed|\Pitchart\Transformer\Composition
 */
function mapcat(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return compose(map($callback), cat());
    }

    return transduce(compose(map($callback), cat()), to_array(), $sequence);
}

/**
 * @param null|mixed $sequence
 * @return \Closure
 */
function flatten($sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) {
            return new Reducer\Flatten($reducer);
        };
    }

    return transduce(flatten(), to_array(), $sequence);
}

/**
 * @param int $number
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function take(int $number, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($number) {
            return new Reducer\Take($reducer, $number);
        };
    }

    return transduce(take($number), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function take_while(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\TakeWhile($reducer, $callback);
        };
    }

    return transduce(take_while($callback), to_array(), $sequence);
}

/**
 * @param int $frequency
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function take_nth(int $frequency, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($frequency) {
            return new Reducer\TakeNth($reducer, $frequency);
        };
    }

    return transduce(take_nth($frequency), to_array(), $sequence);
}

/**
 * @param int $number
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function drop(int $number, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($number) {
            return new Reducer\Drop($reducer, $number);
        };
    }

    return transduce(drop($number), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function drop_while(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\DropWhile($reducer, $callback);
        };
    }

    return transduce(drop_while($callback), to_array(), $sequence);
}

/**
 * @param int $page
 * @param int $numberOfItems
 * @param null|iterable $sequence
 *
 * @return array|\Closure
 */
function paginate($page = 1, $numberOfItems = 10, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($page, $numberOfItems) {
            return new Reducer\Paginate($reducer, $page, $numberOfItems);
        };
    }

    return transduce(paginate($page, $numberOfItems), to_array(), $sequence);
}

/**
 * @param array $map
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function replace(array $map, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($map) {
            return new Reducer\Replace($reducer, $map);
        };
    }

    return transduce(replace($map), to_array(), $sequence);
}

/**
 * @param null|mixed $sequence
 * @return \Closure
 */
function distinct($sequence = null, ?callable $callback = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Distinct($reducer, $callback);
        };
    }

    return transduce(distinct(null, $callback), to_array(), $sequence);
}

/**
 * @param null|mixed $sequence
 * @return \Closure
 */
function dedupe($sequence = null, ?callable $callback = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Dedupe($reducer, $callback);
        };
    }

    return transduce(dedupe(null, $callback), to_array(), $sequence);
}

/**
 * @param int $size
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function partition(int $size, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($size) {
            return new Reducer\Partition($reducer, $size);
        };
    }

    return transduce(partition($size), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function partition_by(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\PartitionBy($reducer, $callback);
        };
    }

    return transduce(partition_by($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|mixed $sequence
 *
 * @return \Closure
 */
function group_by(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\GroupBy($reducer, $callback);
        };
    }

    return transduce(group_by($callback), to_single(), $sequence);
}

/**
 * @param callable $callback
 * @param null $sequence
 *
 * @return array|\Closure|mixed
 */
function sort(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\Sort($reducer, $callback);
        };
    }
    if (is_array($sequence)) {
        \usort($sequence, $callback);

        return $sequence;
    }

    return transduce(sort($callback), to_array(), $sequence);
}

/**
 * @param callable $callback
 * @param null|iterable $sequence
 *
 * @return null|array|\Closure|mixed
 */
function sort_by(callable $callback, $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($callback) {
            return new Reducer\SortBy($reducer, $callback);
        };
    }
    if (\is_array($sequence)) {
        \usort($sequence, comparator($callback));

        return $sequence;
    }

    return transduce(sort_by($callback), to_array(), $sequence);
}

function merge(iterable $collection, ?iterable $sequence = null)
{
    if ($sequence === null) {
        return static function (Reducer $reducer) use ($collection) {
            return new Reducer\Merge($reducer, $collection);
        };
    }
    if (!\is_array($sequence)) {
        $sequence = \iterator_to_array($sequence);
    }
    if (!\is_array($collection)) {
        $collection = \iterator_to_array($collection);
    }
    return \array_merge($collection, $sequence);
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
 * @param callable $callback
 *
 * @return Reducer\Termination\Has
 */
function has(callable $callback)
{
    return new Reducer\Termination\Has($callback);
}

/**
 * @param mixed $glue
 *
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
