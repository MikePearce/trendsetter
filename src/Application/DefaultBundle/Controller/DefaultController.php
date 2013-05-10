<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Stories;
use Application\DefaultBundle\Lib\Velocity;
use Application\DefaultBundle\Lib\Estimates;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{    
    public function indexAction()
    {        
        return $this->render('ApplicationDefaultBundle:Default:index.html.twig');
    }

    /**
     * Generic data endpoint
     * @return response object
     **/
    public function dataAction($type, $backlog = false, $storyid = 0) {

      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $easybacklogClient->setAccountId('477')
                        ->setBacklog($backlog);
      
      // This switch feeds all the data, maybe there is a better way of doing this...
      switch($type) {
        // Homepage
        case 'general-deptstats':
            $vel = new Velocity($easybacklogClient, $this->get('memcached'));
            $str = new Stories($easybacklogClient, $this->get('memcached'));
            $data = array(
                'current_velocity'      => $vel->getCurrentVelocity(),
                'current_acceptance'    => $str->getCurrentAcceptanceRate(),
            );
            break;
        case 'general-teamstats':
            $vel = new Velocity($easybacklogClient, $this->get('memcached'));
            $stories = new Stories($easybacklogClient, $this->get('memcached'));
            $data = array();
            foreach($this->container->getParameter('teams') AS $team) {
                $easybacklogClient->setBacklog($team['backlog']);
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
          break;
        case 'story-single':
          $stories = new Stories($easybacklogClient);
          $data = $stories->getSingleStory($storyid);
          break;

        default:
          throw new \Exception("I don't know what ". $type ."is.", 1);
          
      }

      $response = new JsonResponse();
      $response->setData($data);
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

}
