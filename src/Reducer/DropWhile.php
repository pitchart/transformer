<?php

namespace Pitchart\Transformer\Reducer;


use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class DropWhile implements Reducer
{
    use HasCallback;

    private $stopDroping = false;

    public function init()
    {
        $this->stopDroping = false;
        return $this->next->init();
    }

    public function step($result, $current)
    {
        if ($this->stopDroping === true) {
            return $this->next->step($result, $current);
        }

        if (!($this->callback)($current)) {
            $this->stopDroping = true;
            return $this->next->step($result, $current);
        }

        return $result;
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }

}