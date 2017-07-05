<?php

namespace Pitchart\Transformer\Reducer\Traits;

use Pitchart\Transformer\Reducer;

trait IsStateless
{
    public function init()
    {
        return $this->next->init();
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }
}
