<?php

namespace Pitchart\Transformer;

interface Reducer
{

    /**
     * should call the init arity on the nested transform xf,
     * which will eventually call out to the transducing process
     *
     * @return mixed
     */
    public function init();

    /**
     * standard reduction function
     * but it is expected to call the xf step arity 0 or more times as appropriate in the transducer.
     *
     * @param $result
     * @param $current
     *
     * @return mixed
     */
    public function step($result, $current);

    /**
     * some processes will not end, but for those that do (like transduce),
     * the completion arity is used to produce a final value and/or flush state
     *
     * @param $result
     *
     * @return mixed
     */
    public function complete($result);
}
