<?php namespace Push_Notification\phones;

/**
 * PHP IOS Push Notification Server Class
 * 
 * @package IOS
 * @since 1.0.0
 * @author M.Tuhin <tuhin@codesolz.net>
 */

use Push_Notification\Push_Notification; 

class Ios{
    
    private $Push_Notification;
    private $apiUrl = [
        'development' => "https://api.development.push.apple.com",
        "production" => "https://api.push.apple.com"
    ];
    private $urlSegment = "%s/3/device/%s"; 
    private $serverPort = 443; //default port 443
    private $appleCert;
    private $appBundleId;
    private $appleCertPasspharase;
    private $deviceToken;
    private $payload;


    public function __construct(Push_Notification $Push_Notification){
        $this->Push_Notification = $Push_Notification;
    }

    /**
     * Setup Configuration
     *
     * @param [array] $config
     * @return void
     */
    public function setConfig( $config ){
        if( ! is_array( $config ) ){
            return 'Please enter correct configuration array';
        }

        if( ! isset( $config['appBundleId'] ) || empty( $appBundleId = $config['appBundleId'] ) ){
            return 'Please enter your app bundle ID';
        }

        //check device token
        if( ! isset( $config['deviceToken'] ) || empty( $deviceToken = $config['deviceToken'] ) ){
            return 'Please enter a device token';
        }

        if( ! isset( $config['passPhrase'] ) || empty( $passPhrase = $config['passPhrase'] ) ){
            return "Please enter passPhrase what you've used to create .pem file";
        }

        if( ! isset( $config['pemFileAbsolutePath'] ) || empty( $pemFile = $config['pemFileAbsolutePath'] )  ){
            return "Please enter .pem file path location";
        }
        else if( false === \file_exists( $pemFile = $config['pemFileAbsolutePath'] ) && false === \file_exists( $pemFile = dirname( __FILE__ ) . $pemFile ) ){
            return "Please enter .pem file valid path location";
        }

        $this->deviceToken            = trim( $deviceToken );
        $this->appleCertPasspharase   = trim( $passPhrase );
        $this->appBundleId            = trim( $appBundleId );
        $this->appleCert              = trim( $pemFile );
        $this->serverPort             = isset( $config['server_port'] ) && !empty( $server_port = $config['server_port'] ) ? 
                                            $server_port : $this->serverPort;
    }
    
    /**
     * Set Payload 
     * - check apple supported payload array
     *
     * @param [type] $payload_array
     * @return void
     */
    public function setPayload( $payload_array ){
        if( empty( $payload_array )  || ! \is_array( $payload_array ) ){
            return "Please enter payload array";
        }

        //check payload array
        if( ! isset( $payload_array['aps'] ) || empty( $payload_array['aps'] ) ){
            return 'Please enter valid payload array';
        }

        if( ! isset( $payload_array['aps']['alert'] ) || empty( $payload_array['aps']['alert'] ) ){
            return 'Please enter valid payload array';
        }

        $this->payload = \json_encode( $payload_array );
    }


    /**
     * Send notification
     *
     * @return void
     */
    public function send(){
        try {
            $dataArr = array(
                'headers' => array(
                    "apns-topic: {$this->appBundleId}",
                    "User-Agent: My Sender"
                ),
                'message' => $this->payload,
                'sslcert' => $this->appleCert,
                'sslkeypasswd' => $this->appleCertPasspharase,
                'serverPort' => $this->serverPort,
                'apiUrl' => sprintf( $this->urlSegment, $this->apiUrl[ $this->Push_Notification->enviroment ], $this->deviceToken )
            );
            return $this->Push_Notification->useCurl( $dataArr );
        } catch (Exception $th) {
            return $th->getMessage();
        }
    }



}



?>
