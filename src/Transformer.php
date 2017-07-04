<?php

namespace Pitchart\Transformer;

use Pitchart\Transformer\Exception\InvalidArgument;
use Pitchart\Transformer\Reducer\Cat;
use Pitchart\Transformer\Reducer\Filter;
use Pitchart\Transformer\Reducer\First;
use Pitchart\Transformer\Reducer\Map;
use Pitchart\Transformer\Reducer\Termination\SingleResult;
use Pitchart\Transformer\Reducer\Termination\ToArray;
use Pitchart\Transformer\Transducer as t;

class Transformer
{
    /**
     * @var Composition
     */
    private $composition;

    /**
     * @var Termination
     */
    private $termination;

    /**
     * @var iterable
     */
    private $iterable;

    /**
     * @var mixed
     */
    private $initial;

    /**
     * Transformer constructor.
     *
     * @param iterable    $iterable
     * @param Composition $composition
     * @param Termination $termination
     * @param mixed       $initial
     */
    public function __construct($iterable, Composition $composition = null, Termination $termination = null, $initial = null)
    {
        if ($composition === null) {
            $composition = new Composition();
        }

        $this->composition = $composition;
        $this->termination = $termination;
        $this->iterable = $iterable;
        $this->initial = $initial;
    }

    /**
     * @param callable $callable
     */
    public function map(callable $callable)
    {
        return new static($this->iterable, $this->composition->append(
            function (Reducer $reducer) use ($callable) {
                return new Map($reducer, $callable);
            }
        ), $this->termination, $this->initial);
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function filter(callable $callable)
    {
        return new static($this->iterable, $this->composition->append(t\filter($callable)), $this->termination, $this->initial);
    }

    public function select(callable $calable)
    {
        return $this->filter($calable);
    }

    public function remove(callable $callable)
    {
        return new static($this->iterable, $this->composition->append(
            function (Reducer $reducer) use ($callable) {
                return new Filter($reducer, function($item) use($callable) {
                    return !($callable($item));
                });
            }
        ), $this->termination, $this->initial);
    }

    public function reject(callable $callable)
    {
        return $this->remove($callable);
    }

    public function first(callable $callable)
    {
        return new static($this->iterable, $this->composition->append(
            function (Reducer $reducer) use ($callable) {
                return new First($reducer, $callable);
            }
        ), $this->termination, $this->initial);
    }

    public function cat()
    {
        return new static($this->iterable, $this->composition->append(
            function (Reducer $reducer) {
                return new Cat($reducer);
            }
        ), $this->termination, $this->initial);
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        return $this->transduce($this->composition, new ToArray(), $this->iterable, $this->initial);
    }

    /**
     * @return mixed
     */
    public function single()
    {
        return $this->transduce($this->composition, new SingleResult(), $this->iterable, $this->initial);
    }

    /**
     * @param callable    $transducer
     * @param Termination $reducer
     * @param array       $iterable
     * @param mixed       $initial
     *
     * @return mixed
     */
    private function transduce(callable $transducer, Termination $reducer, $iterable, $initial = null)
    {
        InvalidArgument::assertIterable($iterable, static::class, __FUNCTION__, 3);
        /** @var Reducer $transformation */
        $transformation = $transducer($reducer);

        $accumulator = ($initial === null) ? $transformation->init() : $initial;

        foreach ($this->generator($iterable) as $current) {
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
     * @param $iterable
     *
     * @return \Generator
     */
    private function generator($iterable)
    {
        yield from $iterable;
    }
}
