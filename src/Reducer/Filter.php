<?php

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class Filter implements Reducer
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
        $callback = $this->callback;
        if ($callback($current)) {
            return $this->next->step($result, $current);
        }
        return $result;
    }
}
