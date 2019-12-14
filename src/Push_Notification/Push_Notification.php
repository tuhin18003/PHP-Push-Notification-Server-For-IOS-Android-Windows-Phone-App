<?php namespace Push_Notification;

/**
 * PHP Push Notification Server
 * 
 * @since 1.0.0
 * @author M.Tuhin <tuhin@codesolz.net>
 */


class Push_Notification{

    protected $phones = [];
    public $enviroment;

     /**
     * Initializes a Push Notification with an
     * enviroment.
     * Valid values: development, production
     *
     * @param string $enviroment (production or developement)
     */
    public function __construct($enviroment)
    {
        if($enviroment !== 'development' && $enviroment !== 'production')
        throw new Exception('The Enviroment needs to be set to either development oder production');

        $this->enviroment = $enviroment;
    }


    /**
     * Call Classes
     * 
     * @param type $name
     * @param type $arguments
     * @return type
     */
    public function __call( $name, $arguments ) {
        return $this->getClass( ucwords( $name ) );
    }
    
    /**
     * Get classes
     * 
     * @param string $class
     * @return type
     */
    private function getClass( $class ){
        $class_path = '\Push_Notification\\phones\\' . $class;

        if( ! class_exists( $class_path ) ){
            return " Class / Method - '{$class_path}' - not found!";
        }
        
        if ( ! array_key_exists( $class_path, $this->phones ) ) {
            $this->phones[ $class_path ] = new $class_path( $this );
        }

        return $this->phones[ $class_path ];
    }

    /**
     * Build url segment
     *
     * @param [string] $segment
     * @param [array] $args
     * @return String
     */
    public function BuildUrl( $segment, $args ){
        return \sprintf( $segment, implode(",", $args ) );
    }

    /**
     * Use Curl to send notification
     *
     * @param [array] $args
     * @return void
     */
    public function useCurl( $args ){
        // open connection
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }
        $http2ch = curl_init();
        curl_setopt( $http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        // other curl options
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => isset( $args['apiUrl']) ? $args['apiUrl'] : '',
            CURLOPT_PORT => isset( $args['serverPort']) ? $args['serverPort'] : '',
            CURLOPT_HTTPHEADER => isset( $args['headers']) ? $args['headers'] : '',
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => isset( $args['message']) ? $args['message'] : '',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => isset( $args['sslcert']) ? $args['sslcert'] : '',
            CURLOPT_SSLKEYPASSWD => isset( $args['sslkeypasswd']) ? $args['sslkeypasswd'] : '',
            CURLOPT_HEADER => 1
        ));

        // go...
        $result = curl_exec($http2ch);
        if ($result === FALSE) {
            return 'Curl failed with error: ' . curl_error($http2ch);
        }
        // get respnse
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);

        curl_close($http2ch);

        return $status;
    }


}

?>