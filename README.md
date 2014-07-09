Google Cloud Messaging (GCM) PHP Server Library
--------------------------------------------

A PHP library for sending messages to devices registered through Google Cloud Messaging.

Requirements:
 - PHP >=5.3.2
 - Redis database

Libraries used:
 - chrisboulton/php-resque 1.2.x (MIT)
 - php-curl-class/php-curl-class 2.1.x (No License)

See:
http://developer.android.com/guide/google/gcm/index.html

Example usage
-----------------------
```php

use \CodeMonkeysRu\GCM;

GCM\Client::configure("YOUR GOOGLE API KEY", 'MyQueueJob');

$message = Message::fromArray(array(
            'registration_ids' => array('device_registration_id1', 'device_registration_id2'),
            'data' => array('data1' => 123, 'data2' => 'string'),
        ));

//This can all be set in the original fromArray call.
$message
    ->setCollapseKey('collapse_key')
    ->setDelayWhileIdle(true)
    ->setTimeToLive(123)
    ->setRestrictedPackageName("com.example.trololo")
    ->setDryRun(true);

//Enqueues the message
Client::send($message);

```





ChangeLog
----------------------
* v0.1 - Initial release

Licensed under MIT license.
