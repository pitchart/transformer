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
     *
     * return static
     */
    public function map(callable $callable)
    {
        return $this->appendComposition(t\map($callable));
    }

    /**
     * @param callable $callable
     *
     * @return $this
     */
    public function filter(callable $callable)
    {
        return $this->appendComposition(t\filter($callable));
    }

    public function select(callable $calable)
    {
        return $this->filter($calable);
    }

    public function keep(callable $callback)
    {
        return $this->appendComposition(t\keep($callback));
    }

    public function remove(callable $callable)
    {
        return $this->appendComposition(t\remove($callable));
    }

    public function reject(callable $callable)
    {
        return $this->remove($callable);
    }

    public function first(callable $callable)
    {
        return $this->appendComposition(t\first($callable));
    }

    public function cat()
    {
        return $this->appendComposition(t\cat());
    }

    public function mapcat(callable $callable)
    {
        return $this->appendComposition(t\mapcat($callable));
    }

    public function flatten()
    {
        return $this->appendComposition(t\flatten());
    }

    public function take(int $number)
    {
        return $this->appendComposition(t\take($number));
    }

    public function takeWhile(callable $callback)
    {
        return $this->appendComposition(t\take_while($callback));
    }

    public function drop(int $number)
    {
        return $this->appendComposition(t\drop($number));
    }

    public function dropWhile(callable $callback)
    {
        return $this->appendComposition(t\drop_while($callback));
    }

    public function distinct()
    {
        return $this->appendComposition(t\distinct());
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        return $this->terminate(t\to_array());
    }

    /**
     * @return mixed
     */
    public function single()
    {
        return $this->terminate(t\to_single());
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

    /**
     * Returns a new Transformer with adding a callaback to its composition
     *
     * @param callable $callback
     *
     * @return static
     */
    private function appendComposition(callable $callback)
    {
        return new static($this->iterable, $this->composition->append($callback), $this->termination, $this->initial);
    }

    /**
     * Processes transducing with a Termination
     *
     * @param Termination $termination
     *
     * @return mixed
     */
    private function terminate(Termination $termination)
    {
        return $this->transduce($this->composition, $termination, $this->iterable, $this->initial);
    }
}
