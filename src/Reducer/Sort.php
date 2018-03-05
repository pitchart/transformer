<?php

namespace Pitchart\Transformer\Reducer;


use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;

class Sort implements Reducer
{
    use Reducer\Traits\HasCallback;

    /**
     * @var array
     */
    protected $store = [];

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        $this->store[] = $current;
        return $result;
    }

    public function complete($result)
    {
        if (count($this->store)) {
            usort($this->store, $this->callback);
            foreach ($this->store as $current) {
                $item = $this->next->step($result, $current);
                if ($item instanceof Reduced) {
                    return $this->next->complete($item);
                }
                $result = $item;
            }
        }

        return $result;
    }
}