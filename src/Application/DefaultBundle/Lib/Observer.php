<?php

namespace Application\DefaultBundle\Lib;

Use Symfony\Component\DependencyInjection\ContainerAware;
Use Application\DefaultBundle\Lib\Velocity;
Use Application\DefaultBundle\Lib\Estimates;
Use Application\DefaultBundle\Lib\Stories;
Use Application\DefaultBundle\Lib\FullScreen;

/**
 * Generates the data, huzzah!
 *
 * @author Mike Pearce <mike@mikepearce.net>
 */
class Observer extends ContainerAware {
    
    /**
     * 
     * @param obj $ebClient
     * @param obj $memcached
     * @param mixed|array/int $teams
     */
    public function __construct($ebClient, $memcached, $teams) {
        $this->easybacklogClient = $ebClient;
        $this->memcached = $memcached;
        $this->teams = $teams;
    }
    
    /**
     * 
     * @param string $type
     * @param int $backlog
     * @param int $storyid
     */
    public function getData($type, $storyid) {
        
        if (method_exists($this, $type)) {
            $this->storyid = $storyid;
            return $this->$type();
        }
        else {
            throw new \Exception('What is this I don\'t even?!');
        }
    }
    
    public function fullscreen() {
        $fullscreen = new FullScreen($this->memcached);
        return $fullscreen->getData();
    }
    
    /**
     * @return Array
     */
    public function backlogtotalstoriespermonth() {
        $estimates = new Estimates($this->easybacklogClient);
        return $estimates->gettotalStoriesPerMonth();
    }
    
    /**
     * 
     * @return Array
     */
    public function acceptancerate() {
        $stories = new Stories($this->easybacklogClient);
        return $stories->getAcceptanceRateForGoogleVis();
    }
    
    /**
     * 
     * @return Array
     */
    public function stories() {
        $stories = new Stories($this->easybacklogClient);
        return $stories->getStoriesByBacklog();
    }
    
    /**
     * 
     * @return Array
     */
    public function backlogestimatespread() {
        $estimates = new Estimates($this->easybacklogClient);
        return $estimates->getEstimateSpreadPerMonth();
    }
    
    /**
     * 
     * @return Array
     */
    public function totalstoriespermonth() {
        $estimates = new Estimates($this->easybacklogClient);
        return $estimates->gettotalStoriesPerMonth();
    }
    
    /**
     * 
     * @return array
     */
    public function departmentvelocity() {
        $velocity = new Velocity($this->easybacklogClient);
        return $velocity->getVelocityForGoogleVis();
    }

    /**
     * Get a single story
     * @return array
     */
    private function story() {
        $stories = new Stories($this->easybacklogClient);
        return $stories->getSingleStory($this->storyid);
    }


    /**
     * Pull the department stats
     * @return array
     */
    private function deptstats() {
        $vel = new Velocity($this->easybacklogClient, $this->memcached);
        $str = new Stories($this->easybacklogClient, $this->memcached);
        return array(
            'current_velocity'      => $vel->getCurrentVelocity(),
            'current_acceptance'    => $str->getCurrentAcceptanceRate(),
        );
    }
    
    /**
     * Get the team stats
     * @todo Should I be doing this loop somewhere else? Stories perhaps?
     * @return array
     */
    private function teamstats() {
        $vel = new Velocity($this->easybacklogClient, $this->memcached);
        $stories = new Stories($this->easybacklogClient, $this->memcached);
        $data = array();
        foreach($this->teams AS $team) {
            $this->easybacklogClient->setBacklog($team['backlog']);
            $counter = $totalpoints = 0;
            // Velocity
            foreach($vel->getCurrentTeamVelocity($team['backlog']) AS $months) {
                $counter += count($months);
                $totalpoints += array_sum($months);
            }

            $data[$team['backlog']] = array(
              'name' => $team['name'], 
              'velocity' => ceil($totalpoints / $counter),
              'acceptance' => $stories->getCurrentAcceptanceRate($team['backlog'])
            );
       }
       
       return $data;
    }
}