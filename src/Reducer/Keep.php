<?php

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class Keep implements Reducer
{
    use IsStateless,
        HasCallback;

    public function step($result, $current)
    {
        if (($this->callable)($current) !== null) {
            return  $this->next->step($result, $current);
        }
        return $result;
    }
}