<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer\Traits;

trait IsStateless
{
    public function init()
    {
        return $this->next->init();
    }

    public function complete($result)
    {
        return $this->next->complete($result);
    }
}
