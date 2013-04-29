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
     * Memcached client
     **/
    private $memcached;


    /**
     * @param $memcached memcache - DI injected
     * @param $guzzle guzzle - DI injected $guzzle
     * @param $api_key string - Your easybacklog.com API key
     * @param $userid int - your user id
     * @return void
     **/
    public function __construct($memcached, $guzzle, $api_key, $userid) {
        
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
     * @param $path string - the URL endpoint
     * @return string - json
     **/
    public function getJsonFromApi($path) {
        print 'GETTING JSON';
        $json =  $this->guzzle->get($path)
                              ->setAuth($this->userid, $this->api_key)
                              ->send()
                              ->getBody();  

        $this->addDataToCache(md5($path), $json);
        return $json;
    }

    /**
     * Whatever it is, construct the endpoint and return the json
     * @param $path string - The path of the call.
     * @return array - The Json as data
     **/
    private function getDataApiData($path = null) {

        // No json, get some
        $json = $this->memcached->get(md5($path));
        if ($json == false) {
            var_dump($json);
            $json =  $this->getJsonFromApi($path);
        }
        else {

            $data = json_decode($json, true);

            if ($data['date'] <= strtotime('-24 hours')) {
                $json =  $this->getJsonFromApi($path);
            }
    
        }

        return json_decode($json, true);
        
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