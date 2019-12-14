<?php

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



?>
