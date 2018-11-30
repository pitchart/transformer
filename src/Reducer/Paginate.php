<?php

/*
 * This file is part of the pitchart/transformer library.
 * (c) Julien VITTE <vitte.julien@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Pitchart\Transformer\Reducer;

use Pitchart\Transformer\Reducer;

class Paginate implements Reducer
{
    /**
     * @var Reducer
     */
    private $next;

    /**
     * @var int
     */
    private $currentPage = 1;

    /**
     * @var int
     */
    private $numberOfItems = 10;

    /**
     * @var int
     */
    private $counter = 0;

    /**
     * Paginate constructor.
     *
     * @param Reducer $next
     * @param int $currentPage
     * @param int $numberOfItems
     */
    public function __construct(Reducer $next, $currentPage, $numberOfItems)
    {
        $this->next = $next;
        $this->currentPage = $currentPage;
        $this->numberOfItems = $numberOfItems;
    }

    public function init()
    {
        $this->counter = 0;

        return $this->next->init();
    }

    public function step($result, $current)
    {
        if ($this->counter >= ($this->currentPage -1) * $this->numberOfItems
        && $this->counter < ($this->currentPage) * $this->numberOfItems) {
            $return = $this->next->step($result, $current);
        } else {
            $return = $result;
        }
        $this->counter++;

        return $return;
    }

    public function complete($result)
    {
        $this->counter = 0;

        return $this->next->complete($result);
    }
}
