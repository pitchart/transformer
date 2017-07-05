<?php

namespace Pitchart\Transformer\Reducer\Traits;



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
