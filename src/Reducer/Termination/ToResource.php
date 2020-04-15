<?php


namespace Pitchart\Transformer\Reducer\Termination;


use Pitchart\Transformer\Termination;

class ToResource implements Termination
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * ToResource constructor.
     *
     * @param callable $callback
     */
    public function __construct(callable $callback = null)
    {
        if ($callback == null) {
            $callback = 'Pitchart\Transformer\identity';
        }
        $this->callback = $callback;
    }

    public function init()
    {
        return fopen('php://temp', 'w+');
    }

    /**
     * @param resource $result
     * @param mixed $current
     */
    public function step($result, $current)
    {
        fwrite($result, (string) ($this->callback)($current));
        return $result;
    }

    public function complete($result)
    {
        return $result;
    }

}