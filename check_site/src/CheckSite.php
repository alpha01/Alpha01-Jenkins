<?php

# Simple mobile and desktop http request library.


class CheckSite
{
    /**
    * Property where test result output is saved too.
    */
    public $test_status = array();


    /** User Agents */
    private $_mobile_ua  = 'Mozilla/5.0 (Linux; U; Android 5.5.5; Nexus 5 Build/KTU84P) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 Jenkins';
    private $_desktop_ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.100 Safari/537.36 Jenkins';

    protected $_site_config;

    public function __construct($site_config=null)
    {
        if ($site_config==null){
            throw new Exception("No site config object provided.\n");
        }

        $this->_site_config = $site_config;
    }

    public function check_site()
    {
        $desktop = $this->get_page('desktop');
        $this->test_status['desktop_http_response_code'] = $desktop[0];
        $this->test_status['desktop_http_response']      = $desktop[1];

        $mobile = $this->get_page('mobile');
        $this->test_status['mobile_http_response_code'] = $mobile[0];
        $this->test_status['mobile_http_response']      = $mobile[1];
    }

    private function get_page($platform)
    {
        if ($platform == 'desktop')
            $user_agent = $this->_desktop_ua;
        if ($platform == 'mobile')
            $user_agent = $this->_mobile_ua;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_site_config['DOMAIN'] . '/?' . time());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 65);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        try {
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false)
                throw new Exception( curl_error($ch) );

        } catch (Exception $e) {
            echo "Curl request error: '" . $this->_site_config['DOMAIN'] . "' " . $e->getMessage() . "\n";
            exit(1);
        }

        return array($http_code, $response); 
    }

}

?>
