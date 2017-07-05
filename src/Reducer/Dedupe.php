<?php

namespace Pitchart\Transformer\Reducer;


use Pitchart\Transformer\Reducer;

class Dedupe implements Reducer
{
    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var null|mixed
     */
    private $last = null;

    /**
     * @var bool
     */
    private $started = false;

    public function __construct(Reducer $next)
    {
        $this->next = $next;
    }

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        if (!$this->started || $current !== $this->last) {
            $return = $this->next->step($result, $current);
        }
        else {
            $return = $result;
        }
        $this->started = true;
        $this->last = $current;

        return $return;
    }

    public function complete($result)
    {
        $this->last = null;
        $this->started  = false;
        return $this->next->complete($result);
    }


}