<?php

namespace Pitchart\Transformer;

class Reduced
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * Reduced constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }
}
