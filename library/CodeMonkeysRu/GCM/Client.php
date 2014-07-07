<?php

namespace CodeMonkeysRu\GCM;

/**
 * Class Client
 *
 * @package CodeMonkeysRu\GCM
 * @author Steve Tauber <taubers@gmail.com>
 */
class Client {
    /**
     * GCM URL.
     *
     * @var string
     */
    protected $gcmUrl = 'https://android.googleapis.com/gcm/send';

    /**
     * Queue Name.
     *
     * @var string
     */
    protected $queueName = 'gcmDefault';

    /**
     * An API key that gives the application server authorized access to Google services.
     *
     * @var string
     */
    protected $serverApiKey = '';

    /**
     * @param string $serverApiKey An API key that gives the application server authorized access to Google services.
     * @param mixed  $server Host/port combination separated by a colon, DSN-formatted URI, or a nested array of
     *                       servers with host/port pairs.
     * @param int    $database ID of Redis Database to select.
     * @param string $queueName Queue Name
     * @param mixed  $gcmUrl GCM URL.
     */
    public function __construct($serverApiKey, $server = 'localhost:6379', $database = 0, $queueName = null, $gcmUrl = false) {
        \Resque::setBackend($server, $database);

        $this->serverApiKey = $serverApiKey;

        if($queueName) {
            $this->queueName = $queueName;
        }

        if ($gcmUrl) {
            $this->gcmUrl = $gcmUrl;
        }
    }

    /**
     * @param \CodeMonkeysRu\GCM\Message $message
     */
    public function send(Message $message) {
        \Resque::enqueue(
            $this->queueName,
            'CodeMonkeysRu\GCM\DefaultSendJob',
            array(
                'gcmUrl' => $this->gcmUrl,
                'serverApiKey' => $this->serverApiKey,
                'message' => $message->toArray()
            )
        );
    }

}