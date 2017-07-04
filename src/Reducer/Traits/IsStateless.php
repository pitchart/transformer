<?php

namespace Pitchart\Transformer\Reducer\Traits;

use Pitchart\Transformer\Reducer;

trait IsStateless
{
    /**
     * @var Reducer
     */
    private $next;

    public function init()
    {
        return $this->next->init();
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }
}