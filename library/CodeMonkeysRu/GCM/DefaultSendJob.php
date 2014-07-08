<?php

namespace CodeMonkeysRu\GCM;

/**
 * Class DefaultSendJob
 *
 * @package CodeMonkeysRu\GCM
 * @author Steve Tauber <taubers@gmail.com>
 */
abstract class DefaultSendJob {

    public $job;

    public $args;

    public $queue;

    public $response;

    public function perform() {
        if(isset($this->args['delay'])) {
            $now = new \DateTime();
            $delay = \DateTime::createFromFormat('U', $this->args['delay']);
            if($delay > $now) {
                Client::enqueueFromJob($this);
                return;
            }
        }

        $response = Sender::send(Message::fromArray($this->args['message']), $this->args['serverApiKey'], $this->args['gcmUrl']);
        if($response instanceof \DateTime) {
            $this->args['delay'] = $response->format('U');
            Client::enqueueFromJob($this);
        } else if($response instanceof Response) {
            $failed = $response->getFailedIds();
            foreach($failed as $error => $group) {
                switch($error) {
                    case 'Unavailable':
                        foreach($group as $item) {
                            //exponential let off
                        }
                        break;
                    case 'InternalServerError':
                        foreach($group as $item) {
                            throw new Exception('GCM\DefaultSendJob->perform - Unknown Error: ' . $item, Exception::UNKNOWN_ERROR);
                        }
                        break;
                    default:
                        /**
                         * The following error messages should remove the Registration IDs from records.
                         *  - NotRegistered
                         */

                        /**
                         * The following error messages are malformed requests:
                         *  - InvalidDataKey
                         *  - InvalidPackageName
                         *  - InvalidRegistration
                         *  - MismatchSenderId
                         *  - MissingRegistration
                         */

                        /**
                         * The follow error messages should never occur since they are explicitly tested for:
                         *  - InvalidTtl
                         *  - MessageTooBig
                         */
                        break;
                }
            }
            $this->response = $response;
        }
    }

    /**
     * Check for NotRegistered error message and remove from records.
     *
     * Example:
     *
     *   $failed = $this->response->getFailedIds();
     *   foreach($failed['NotRegistered'] as $f) { ... }
     *
     * @return mixed
     */
    public abstract function tearDown();

}