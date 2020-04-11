<?php


namespace Pitchart\Transformer\Reducer\Termination;


use Pitchart\Transformer\Termination;
use function Pitchart\Transformer\Transducer\to_array;
use function Pitchart\Transformer\transform;

class ToCsv implements Termination
{
    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var string
     */
    private $enclosure;

    /**
     * @var string
     */
    private $escapeChar;

    /**
     * ToCsv constructor.
     *
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escapeChar
     */
    public function __construct(string $delimiter = ',', string $enclosure = '"', string $escapeChar = "\\")
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escapeChar = $escapeChar;
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
        if (!is_array($current)) {
            if (is_iterable($current)) {
                $current = transform($current)->toArray();
            }
            else {
                $current = [(string) $current];
            }
        }
        fputcsv($result, $current, $this->delimiter, $this->enclosure, $this->escapeChar);
        return $result;
    }

    public function complete($result)
    {
        return $result;
    }
}