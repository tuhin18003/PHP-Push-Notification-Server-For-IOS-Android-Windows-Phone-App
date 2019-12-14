

<p align="center">
<img src="https://codesolz.net/packages/uploads/2019/12/push-notification.jpeg" height="200" alt="Hits">
</p>

<h2 align="center">Custom push notification server for IOS, Android &amp; Windows phone APP with PHP</h2>
<img src="https://hitcounter.pythonanywhere.com/count/tag.svg?url=https%3A%2F%2Fgithub.com%2Ftuhin18003%2FPHP-Push-Notification-Server-For-IOS-Android-Windows-Phone-App" alt="Hits">

### What does it do? 

- You can build your custom push notification service from your own php server.


## Installation

#### Bleeding Edge

```json
{
   "require": {
      "tuhin18003/php-push-notification-ios-android": "dev-master"
   }
}
```

#### Via command line:
```shell
composer require tuhin18003/php-push-notification-ios-android
```

#### IOS Push Notification Example:
```code
require './vendor/autoload.php';

$Push_Notification = new Push_Notification\Push_Notification( 'development' );
$Ios_Notification = $Push_Notification->Ios();


$res = $Ios_Notification->setConfig( array( 
    'appBundleId' => 'com.test.com',
    'deviceToken' => 'your device token',
    'passPhrase' => 'your pass',
    'pemFileAbsolutePath' => '/pusher.pem'
) );

 $Ios_Notification->setPayload(
    array(
        'aps' => array(
            'alert' => array(
                'title' => 'test title',
                'body' => 'test body',
            ),
            'badge' => (int)10,
            'sound' => 'default',
            'mutable-content'=> 1,
            'content-available'=>1
       )
    )
);
try {
    
    $push_server_response = $Ios_Notification->send();
    throw new Exception( $push_server_response, 1);

} catch (\Throwable $th) {
    //throw $th;
    echo $th->getMessage();
}
```


### Credentials
- *Created by - [M.Tuhin](https://codesolz.net/)*
- *For any kind of web & apps development contact us & visit our website - [CodeSolz.net](https://codesolz.net/)*

<a href="https://codesolz.net">
  <img src="https://codesolz.net/packages/uploads/2016/11/logo4-hover.png" alt="codesolz.net"/>
</a>
