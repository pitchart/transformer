<?php

namespace Pitchart\Transformer\Reducer;


use Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer\Traits\HasCallback;

class DropWhile implements Reducer
{
    use HasCallback;

    private $stopDropping = false;

    public function init()
    {
        $this->stopDropping = false;
        return $this->next->init();
    }

    public function step($result, $current)
    {
        if ($this->stopDropping === true) {
            return $this->next->step($result, $current);
        }

        if (!($this->callback)($current)) {
            $this->stopDropping = true;
            return $this->next->step($result, $current);
        }

        return $result;
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }

}