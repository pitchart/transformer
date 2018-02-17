<?php

namespace Pitchart\Transformer;

use function Pitchart\Transformer\identity;

/**
 * Class Composition
 *
 * @package Pitchart\Transformer
 *
 * @author Julien VITTE <vitte.julien@gmail.fr>
 */
class Composition
{
    /**
     * @var array
     */
    private $functions = [];

    public function __construct(callable ...$functions)
    {
        $this->functions = $functions;
    }

    /**
     * @param callable[] ...$callback
     *
     * @return Composition
     */
    public function append(callable ...$callback)
    {
        $self = new self();
        $self->functions = array_merge($this->functions, $callback);
        return $self;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        if (empty($this->functions)) {
            return (function ($value) { return $value; })(func_get_arg(0));
        }

        $functionList = array_reverse($this->functions);
        $first = array_shift($functionList);

        return array_reduce(
            $functionList,
            function ($carry, $item) {
                return $item($carry);
            },
            call_user_func_array($first, func_get_args())
        );
    }
}
