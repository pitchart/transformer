<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer\Termination;

use Pitchart\Transformer\Termination;

class ToString implements Termination
{
    /**
     * @var string
     */
    private $glue;

    public function __construct($glue = '')
    {
        $this->glue = $glue;
    }

    public function init()
    {
        return '';
    }

    public function step($result, $current)
    {
        if ((string) $result) {
            return $result.$this->glue.$current;
        }

        return $current;
    }

    public function complete($result)
    {
        return $result;
    }
}
