<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer\Traits;

use Pitchart\Transformer\Reducer;

trait HasCallback
{
    /**
     * @var Reducer
     */
    protected $next;

    /**
     * @var callable
     */
    protected $callback;

    public function __construct(Reducer $next, callable $callback)
    {
        $this->next = $next;
        $this->callback = $callback;
    }
}
