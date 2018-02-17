<?php

namespace Pitchart\Transformer;

use Pitchart\Transformer\Exception\InvalidArgument;
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
     * @param iterable    $iterable
     * @param Composition $composition
     * @param Termination $termination
     * @param mixed       $initial
     */
    public function __construct($iterable, Composition $composition = null, Termination $termination = null, $initial = null)
    {
        InvalidArgument::assertIterable($iterable, static::class, __FUNCTION__, 3);

        if ($composition === null) {
            $composition = new Composition();
        }

        $this->composition = $composition;
        $this->termination = $termination;
        $this->iterable = $iterable;
        $this->initial = $initial;
    }

    /**
     * @param $initial
     *
     * @return static
     */
    public function initialize($initial)
    {
        return new static($this->iterable, $this->composition, $this->termination, $initial);
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function map(callable $callback)
    {
        return $this->appendComposition(t\map($callback));
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function filter(callable $callback)
    {
        return $this->appendComposition(t\filter($callback));
    }

    /**
     * @param callable $calable
     *
     * @return static
     */
    public function select(callable $calable)
    {
        return $this->filter($calable);
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function keep(callable $callback)
    {
        return $this->appendComposition(t\keep($callback));
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function remove(callable $callback)
    {
        return $this->appendComposition(t\remove($callback));
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function reject(callable $callback)
    {
        return $this->remove($callback);
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function first(callable $callback)
    {
        return $this->appendComposition(t\first($callback));
    }

    /**
     * @return static
     */
    public function cat()
    {
        return $this->appendComposition(t\cat());
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function mapcat(callable $callback)
    {
        return $this->appendComposition(t\mapcat($callback));
    }

    /**
     * @return static
     */
    public function flatten()
    {
        return $this->appendComposition(t\flatten());
    }

    /**
     * @param int $number
     *
     * @return static
     */
    public function take(int $number)
    {
        return $this->appendComposition(t\take($number));
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function takeWhile(callable $callback)
    {
        return $this->appendComposition(t\take_while($callback));
    }

    /**
     * @param int $frequency
     *
     * @return static
     */
    public function takeNth(int $frequency)
    {
        return $this->appendComposition(t\take_nth($frequency));
    }

    /**
     * @param int $number
     *
     * @return static
     */
    public function drop(int $number)
    {
        return $this->appendComposition(t\drop($number));
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function dropWhile(callable $callback)
    {
        return $this->appendComposition(t\drop_while($callback));
    }

    /**
     * @param int $page
     * @param int $numberOfItems
     *
     * @return static
     */
    public function paginate($page = 1, $numberOfItems = 10)
    {
        return $this->appendComposition(t\paginate($page, $numberOfItems));
    }

    /**
     * @param array $map
     *
     * @return static
     */
    public function replace(array $map)
    {
        return $this->appendComposition(t\replace($map));
    }

    /**
     * @return static
     */
    public function distinct()
    {
        return $this->appendComposition(t\distinct());
    }

    /**
     * @return static
     */
    public function dedupe()
    {
        return $this->appendComposition(t\dedupe());
    }

    /**
     * @param $size
     *
     * @return static
     */
    public function partition($size)
    {
        return $this->appendComposition(t\partition($size));
    }

    /**
     * @param callable $callback
     *
     * @return Transformer
     */
    public function partitionBy(callable $callback)
    {
        return $this->appendComposition(t\partition_by($callback));
    }

    /**
     * @param callable $callback
     *
     * @return Transformer
     */
    public function groupBy(callable $callback)
    {
        return $this->appendComposition(t\group_by($callback));
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
     * @return mixed
     */
    public function sum()
    {
        return $this->terminate(t\to_operation('+'));
    }

    /**
     * @return mixed
     */
    public function difference()
    {
        return $this->terminate(t\to_operation('-'));
    }

    /**
     * @return mixed
     */
    public function multiply()
    {
        return $this->terminate(t\to_operation('*'));
    }

    /**
     * @return mixed
     */
    public function divide()
    {
        return $this->terminate(t\to_operation('/'));
    }

    /**
     * @return string
     */
    public function concat()
    {
        return $this->terminate(t\to_operation('.'));
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

        return t\transduce($transducer, $reducer, $this->generator($iterable), $initial);
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
     * Returns a new Transformer with adding a callback to its composition
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
