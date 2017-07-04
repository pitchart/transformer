<?php

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class TakeWhile implements Reducer
{
    use IsStateless,
        HasCallback;

    public function step($result, $current)
    {
        if (($this->callable)($current)) {
            return $this->next->step($result, $current);
        }
        return $result instanceof Reduced ? $result : new Reduced($result);
    }
}
