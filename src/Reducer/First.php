<?php

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class First implements Reducer
{
    use IsStateless,
        HasCallback;

    /**
     * @param $result
     * @param $current
     *
     * @return mixed
     */
    public function step($result, $current)
    {
        $callable = $this->callable;
        if ($callable($current)) {
            return new Reduced($this->next->step($result, $current));
        }
        return $result;
    }
}
