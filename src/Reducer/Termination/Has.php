<?php


namespace Pitchart\Transformer\Reducer\Termination;


use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer\Traits\HasCallback;
use Pitchart\Transformer\Termination;

class Has implements Termination
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function init()
    {
        return false;
    }

    public function step($result, $current)
    {
        $callback = $this->callback;
        if ($callback($current)) {
            return new Reduced(true);
        }

        return $result;
    }

    public function complete($result)
    {
        if ($result instanceof Reduced) {
            return $result->value();
        }

        return $result;
    }

}
