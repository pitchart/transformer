<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer\Termination;

use Pitchart\Transformer\Reduced;
use Pitchart\Transformer\Termination;

class SingleResult implements Termination
{
    public function init()
    {
    }

    public function step($result, $current)
    {
        return new Reduced($current);
    }

    public function complete($result)
    {
        if ($result instanceof Reduced) {
            return $result->value();
        }

        return $result;
    }
}
