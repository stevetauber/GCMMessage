<?php
namespace CodeMonkeysRu\GCM;
use Curl\Curl;

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
    public static function send(Message $message, $serverApiKey, $gcmUrl) {
        $curl = new Curl();
        $curl->setUserAgent('CodeMonkeysRu\GCMMessage');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, 1);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, 2);
        $curl->setHeader('Authorization', 'key=' . $serverApiKey);
        $curl->setHeader('Content-Type', 'application/json');
        $curl->post($gcmUrl, $message->toArray(true));

        $curl->close();

        if ($curl->error) {
            $blah = $curl->response_headers;
        }

        $response = $curl->response;

        return new Response($message, $response);



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

    }

}
