<?php

namespace Pitchart\Transformer\Reducer\Traits;

use Pitchart\Transformer\Reducer;

trait HasCallback
{
    /**
     * @var Reducer
     */
    protected $reducer;

    /**
     * @var callable
     */
    protected $callable;

    public function __construct(Reducer $next, callable $callable)
    {
        $this->next = $next;
        $this->callable = $callable;
    }
}
