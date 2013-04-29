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
     * @param $memcached memcache - DI injected
     * @param $guzzle guzzle - DI injected $guzzle
     * @param $api_key string - Your easybacklog.com API key
     * @param $userid int - your user id
     * @return void
     **/
    public function __construct($memcached, $guzzle, $api_key, $userid) {
        // Set it
        $this->guzzle       = $guzzle;
        $this->api_key      = $api_key;
        $this->userid       = $userid;
        $this->memcached    = $memcached;
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

        // Pull the 


        // Then refresh.
        return false;
    }

    /**
     * Whatever it is, construct the endpoint and return the json
     * @param $path string - The path of the call.
     * @return array - The Json as data
     **/
    private function getDataApiData($path = null) {

        $data = $this->refreshData($path);

        // If we need to refresh the data..
        if (!$data) {
            $data = $this->guzzle->get($path)
                         ->setAuth($this->userid, $this->api_key)
                         ->send()
                         ->getBody();

            // Haven't got this, let's save it.
            $this->addDataToCache($path, $data);
        }

        return json_decode($data, true);
        
    }

    /**
     * Add data to the cache (either a file, or maybe mongo)
     * @param $key string - This will be the path, used as the key
     * @param $json string -
     * @return void
     **/
    private function addDataToCache($key, $json) {

        // First, add a timestamp
        $data = json_decode($json, true);
        $data['date'] = time();
        $json = json_encode($data);

        // Then, add it to memcache
        
        $this->memcached->set($key, $json);
    }

    /**
     * @param $include_associated_data boo - 
     */
    public function getThemes($include_associated_data = false) {
        $path = 'api/backlogs/{backlogid}/themes.json';
        if ($include_associated_data) $path .= '?include_associated_data=true';

        $data = array();
        foreach($this->backlogs AS $backlog_id) {
            $data = array_merge($data, $this->getDataApiData(str_replace('{backlogid}', $backlog_id, $path)));
            
        }
        
        return $data;
        
    }

    /**
     * Pull all the stories from a theme (or all themes)
     * @return array
     **/
    public function getStoriesFromTheme() {
        
        $stories = array();
        foreach ($this->getThemes(true) AS $theme) {

            if (is_array($theme['stories'])) {
                $stories = array_merge($stories, $theme['stories']);    
            }
        }

        return $stories;
    }

    

    public function getStoriesFromSprint() {

    }
    
}