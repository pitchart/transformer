<?php

namespace Pitchart\Transformer\Reducer\Traits;

use Pitchart\Transformer\Reducer;

trait HasCallback
{
    /**
     * @var Reducer
     */
    protected $next;

    /**
     * @var callable
     */
    protected $callback;

    public function __construct(Reducer $next, callable $callback)
    {
        $this->next = $next;
        $this->callback = $callback;
    }
}
