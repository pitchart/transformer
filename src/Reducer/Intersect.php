<?php


namespace Pitchart\Transformer\Reducer;


use function Pitchart\Transformer\comparator;
use Pitchart\Transformer\Reducer;

class Intersect implements Reducer
{
    use Reducer\Traits\IsStateless;

    /**
     * @var Reducer
     */
    protected $next;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var iterable
     */
    private $collection;

    public function __construct(Reducer $next, iterable $collection, ?callable $callback = null)
    {
        if ($callback === null) {
            $callback = comparator('Pitchart\Transformer\identity');
        }
        if (!\is_array($collection)) {
            $collection = \iterator_to_array($collection);
        }

        $this->next = $next;
        $this->callback = $callback;
        $this->collection = $collection;
    }


    public function step($result, $current)
    {
        if ($this->isDiff($current)) {
            return $this->next->step($result, $current);
        }
        return $result;
    }

    private function isDiff($item)
    {
        return count(\array_uintersect([$item], $this->collection, $this->callback)) === 1;
    }
}