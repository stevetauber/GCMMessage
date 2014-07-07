<?php

namespace CodeMonkeysRu\GCM;

/**
 * Class DefaultSendJob
 *
 * @package CodeMonkeysRu\GCM
 * @author Steve Tauber <taubers@gmail.com>
 */
class DefaultSendJob {

    public $job;

    public $args;

    public $queue;

    public function perform() {
        $response = Sender::send(Message::fromArray($this->args['message']), $this->args['serverApiKey'], $this->args['gcmUrl']);
        $blah = 1;
    }

}