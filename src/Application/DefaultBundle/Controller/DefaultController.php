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
    public function dataAction($type, $backlog = false) {

      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $easybacklogClient->setAccountId('477')
                        ->setBacklog($backlog);
      
      // This switch feeds all the data, maybe there is a better way of doing this...
      switch($type) {
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
            $data = $vel->getCurrentVelocity();
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
