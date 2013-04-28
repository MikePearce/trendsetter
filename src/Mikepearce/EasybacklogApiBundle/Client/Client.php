<?php

namespace MikePearce\EasybacklogApiBundle\Client;

class Client {

    // HTTP Service
    private $guzzle;

    /**
     * Where are we going to store it?
     **/
    private $json_file = 'stories.json';

    /**
     * Your easybacklog.com API key
     **/
    private $api_key;

    /**
     * You user id
     **/
    private $userid;

    /**
     * Your easybacklog.com account Id
     **/
    public $accountid;

    /**
     * Your easybacklog.com backlogs
     **/
    public $backlogs;


    /**
     * @param $guzzle guzzle - DI injected $guzzle
     * @param $api_key string - Your easybacklog.com API key
     * @param $userid int - your user id
     * @return void
     **/
    public function __construct($guzzle, $api_key, $userid) {
        // Set it
        $this->guzzle       = $guzzle;
        $this->api_key      = $api_key;
        $this->userid       = $userid;
    }

    /**
     * @param $id int - Easy Backlog account ID
     * @return $this object - returns itslf.
     **/
    public function setAccountId($id) {
        $this->accountid = $id;
        return $this;
    }

    /**
     * @param $backlog int|array - Either a backlog ID, or an array of said.
     * @return $this object - Return itself.
     **/
    public function setBacklog($backlog)
    {
        if (!is_array($backlog)) $backlog = array($backlog);

        $this->backlogs = $backlog;

        return $this;
    }

    /**
     * @return string - your uRL endpoint
     **/
    public function getEndpoint() {
        return $this->guzzle->getBaseUrl();
    }

    /**
     * Get the specified JSON from the api
     * @param $path string - The path you want from the API.
     * @return boo
     */
    private function refreshData($path = null) {

        // First, check and see if we NEED to refresh

        // Then refresh.
        return false;
    }

    /**
     * Whatever it is, construct the endpoint and return the json
     * @param $path string - The path of the call.
     **/
    private function getDataFromApi($path = null) {

        $data = $this->refreshData($path)
        // If we need to refresh the data..
        if (!$data) {
            $data = $this->guzzle->get($path)
                         ->setAuth($this->userid, $this->api_key)
                         ->send()
                         ->getBody();

            // Haven't got this, let's save it.
            $this->addDataToCache($path, $data);
        }

        return $data;
        
    }

    /**
     *
     */
    public function getThemes($include_associated_data = false) {
        $path = 'api/backlogs/{backlogid}/themes.json';
        if ($include_associated_data) $path .= '?include_associated_data=true';

        $data = '';
        foreach($this->backlogs AS $backlog_id) {
            $data .= $this->getDataFromApi(str_replace('{backlogid}', $backlog_id, $path));    
        }

        return $data;
        

    }

    public function getStuff() {
        //return $this->getDataFromApi('api/backlogs/7869/themes.json?include_associated_data=true');
    }
    
}