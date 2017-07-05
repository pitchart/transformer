<?php

namespace Pitchart\Transformer;

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
     * @param callable $callback
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
        $functionList = array_reverse($this->functions);
        $first_function = array_shift($functionList);

        return array_reduce(
            $functionList,
            function ($carry, $item) {
                return $item($carry);
            },
            call_user_func_array($first_function, func_get_args())
        );
    }
}
