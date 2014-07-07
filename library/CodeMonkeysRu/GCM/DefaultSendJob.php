<?php

namespace CodeMonkeysRu\GCM;

/**
 * Class DefaultSendJob
 *
 * @package CodeMonkeysRu\GCM
 * @author Steve Tauber <taubers@gmail.com>
 */
class DefaultSendJob {

    protected $job;

    protected $args;

    protected $queue;

    public function perform() {
        //do the sending
        $args = $this->args;
        $job = $this->job;
        $queue = $this->queue;
    }

}