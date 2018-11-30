<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Reducer;
use Pitchart\Transformer\Reducer\Traits\HasCallback;

class PartitionBy implements Reducer
{
    use HasCallback;

    /**
     * @var array
     */
    private $buffer;

    /**
     * @var null
     */
    private $last;

    public function init()
    {
        return $this->next->init();
    }

    public function step($result, $current)
    {
        $evaluation = ($this->callback)($current);
        if ($this->buffer === null) {
            $this->last = $evaluation;
            $this->buffer = [$current];
        } elseif ($this->last !== $evaluation) {
            $this->last = $evaluation;
            if (!empty($this->buffer)) {
                $buffer = $this->buffer;
                $this->buffer = [$current];

                return $this->next->step($result, $buffer);
            }
        } else {
            $this->buffer[] = $current;
        }

        return $result;
    }

    public function complete($result)
    {
        if (!empty($this->buffer)) {
            if ($result instanceof Reduced) {
                $result = $result->value();
            }
            $result = $this->next->step($result, $this->buffer);
        }

        return $this->next->complete($result);
    }
}
