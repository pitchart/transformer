<?php


namespace Pitchart\Transformer\Reducer;


use Pitchart\Transformer\Reducer;

class Merge implements Reducer
{
    /**
     * @var Reducer
     */
    protected $next;

    public function __construct(Reducer $next, iterable $collection)
    {
        $this->next = $next;
        $this->collection = function () use ($collection) { yield from $collection; };
    }

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        return $this->next->step($result, $current);
    }

    public function complete($result)
    {
        foreach (($this->collection)() as $item) {
            $result = $this->next->step($result, $item);
        }
        return $this->next->complete($result);
    }

}