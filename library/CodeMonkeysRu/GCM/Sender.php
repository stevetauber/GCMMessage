<?php
namespace CodeMonkeysRu\GCM;

/**
 * Messages sender to GCM servers
 *
 * @author Vladimir Savenkov <ivariable@gmail.com>
 */
class Sender
{

    /**
     * Send message to GCM
     *
     * @param Message $message The Message to send.
     * @param string  $serverApiKey Server API Key.
     * @param string  $gcmUrl GCM URL.
     *
     * @return Response
     * @throws Exception When
     */
    public function send(Message $message, $serverApiKey, $gcmUrl) {
        $headers = array(
            'Authorization: key=' . $serverApiKey,
            'Content-Type: application/json'
        );



        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->gcmUrl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $resultBody = curl_exec($ch);
        $resultHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        switch ($resultHttpCode) {
            case "200":
                //All fine. Continue response processing.
                break;

            case "400":
                throw new Exception('Malformed request. '.$resultBody, Exception::MALFORMED_REQUEST);
                break;

            case "401":
                throw new Exception('Authentication Error. '.$resultBody, Exception::AUTHENTICATION_ERROR);
                break;

            default:
                //TODO: Retry-after
                throw new Exception("Unknown error. ".$resultBody, Exception::UNKNOWN_ERROR);
                break;
        }

        return new Response($message, $resultBody);
    }

    /**
     * Form raw message data for sending to GCM
     *
     * @param \CodeMonkeysRu\GCM\Message $message
     * @return array
     */
    private function formMessageData(Message $message)
    {
        $data = array(
            'registration_ids' => $message->getRegistrationIds(),
        );

        $dataFields = array(
            'registration_ids' => 'getRegistrationIds',
            'collapse_key' => 'getCollapseKey',
            'data' => 'getData',
            'delay_while_idle' => 'getDelayWhileIdle',
            'time_to_live' => 'getTtl',
            'restricted_package_name' => 'getRestrictedPackageName',
            'dry_run' => 'getDryRun',
        );

        foreach ($dataFields as $fieldName => $getter) {
            if ($message->$getter() != null) {
                $data[$fieldName] = $message->$getter();
            }
        }

        return $data;
    }

}
