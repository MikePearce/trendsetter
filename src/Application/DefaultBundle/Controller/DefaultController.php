<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\DefaultBundle\Lib\Observer;

class DefaultController extends Controller
{    
    public function indexAction()
    {        
        return $this->render(
        'ApplicationDefaultBundle:Default:index.html.twig'
        );
    }

    public function fullscreenAction()
    {        
        return $this->render(
            'ApplicationDefaultBundle:Default:fullscreen.html.twig'
        );
    }

    /**
     * Generic data endpoint
     * @return response object
     **/
    public function dataAction($type, $backlog = false, $storyid = 0) {
        $easybacklogClient = $this->get('mikepearce_easybacklog_api');
        $easybacklogClient->setAccountId('477')
                          ->setBacklog($backlog);
        
        // Get the data
        $observer = new Observer(
            $easybacklogClient, 
            $this->get('memcached'), 
            $this->container->getParameter('teams')
        );

        $response = new JsonResponse();
        $response->setData($observer->getData($type, $storyid));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
