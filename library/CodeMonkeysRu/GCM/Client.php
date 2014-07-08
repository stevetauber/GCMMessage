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
     * Class name of the Job that extends DefaultSendJob.
     *
     * @var string
     */
    protected $sendJob = '';

    /**
     * @param string $serverApiKey An API key that gives the application server authorized access to Google services.
     * @param string $sendJob Class name of the Job that extends DefaultSendJob.
     * @param mixed  $server Host/port combination separated by a colon, DSN-formatted URI, or a nested array of
     *                       servers with host/port pairs.
     * @param int    $database ID of Redis Database to select.
     * @param string $queueName Queue Name
     * @param mixed  $gcmUrl GCM URL.
     */
    public function __construct($serverApiKey, $sendJob, $server = 'localhost:6379', $database = 0, $queueName = null, $gcmUrl = false) {
        \Resque::setBackend($server, $database);

        $this->serverApiKey = $serverApiKey;
        $this->sendJob = $sendJob;

        if($queueName) {
            $this->queueName = $queueName;
        }

        if ($gcmUrl) {
            $this->gcmUrl = $gcmUrl;
        }
    }

    /**
     * @param $job
     */
    public static function enqueueFromJob($job) {
        $blah = 1;
    }

    /**
     * Enqueue the message.
     *
     * @param \CodeMonkeysRu\GCM\Message $message Message to send.
     * @param \DateTime|boolean $delay When to send the message.
     *
     * @return $this
     */
    public function send(Message $message, $delay = false) {
        $args = array(
            'gcmUrl' => $this->gcmUrl,
            'serverApiKey' => $this->serverApiKey,
            'message' => $message->toArray()
        );

        if($delay) {
            $args['delay'] = $delay->format('U');
        }

        \Resque::enqueue(
            $this->queueName,
            $this->sendJob,
            $args
        );

        return $this;
    }

    /**
     * @param string $gcmUrl
     * @return $this
     */
    public function setGcmUrl($gcmUrl)
    {
        $this->gcmUrl = $gcmUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getGcmUrl()
    {
        return $this->gcmUrl;
    }

    /**
     * @param string $queueName
     * @return $this
     */
    public function setQueueName($queueName)
    {
        $this->queueName = $queueName;
        return $this;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * @param string $serverApiKey
     * @return $this
     */
    public function setServerApiKey($serverApiKey)
    {
        $this->serverApiKey = $serverApiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerApiKey()
    {
        return $this->serverApiKey;
    }
}