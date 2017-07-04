<?php

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\IsStateless;

class Cat implements Reducer
{
    use IsStateless;

    /**
     * Cat constructor.
     */
    public function __construct(Reducer $next)
    {
        $this->next = $next;
    }

    public function step($result, $current)
    {
        if (!is_array($current)
            && !($current instanceof \Traversable)
        ) {
            return $this->next->step($result, $current);
        }
        foreach ($current as $item) {
            $result = $this->next->step($result, $item);
        }

        return $result;
    }
}
