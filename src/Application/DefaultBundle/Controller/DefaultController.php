<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\DefaultBundle\Lib\Observer;
use Application\DefaultBundle\Lib\FullScreen;

use Zend\XmlRpc\Client;
use Zend\Http\Client AS HTTPClient;
class DefaultController extends Controller
{    
    public function indexAction()
    {     
        $q = "SELECT *, ";
        $q .=   "id AS ticket, status, priority, owner, ";
//        $q .=   "DATE(FROM_UNIXTIME(time)) as date_created, summary, type, ";
//        $q .=   "DATE(FROM_UNIXTIME(changetime)) as changetimes ";
        $q .= "FROM ticket ";
//        $q .= "WHERE component in ('shopwindow', 'darwin', 'site2', 'shopwindow api', 'shared library', 'reporting') ";
//        $q .= "AND DATE(FROM_UNIXTIME(time)) > '2011-01-01 00:00:00' AND type = 'defect' ORDER BY id";
        
        // Do this 
        $httpclient = NEW HTTPClient();
        $httpclient->setOptions(array('sslverifypeer' => false));
        $client = new Client(
            'http://mike.pearce:marmaset@dtrac.affiliatewindow.com/login/xmlrpc',
            $httpclient
        );
        //$result = $client->call('search.performSearch', $q);
        $result = $client->call('ticket.query', 
            'max=0&component=shopwindow&component=darwin&component=site2&component=reporting&'.
            'component=shopwindow%20api&component=shared%20library&'.
            'type=defect'
        );
        var_dump($result);
       
        
        return $this->render(
        'ApplicationDefaultBundle:Default:index.html.twig'
        );
    }

    public function fullscreenAction()
    {       
        $fullscreen = new FullScreen($this->get('memcached'));
        $fullscreen->getAwinBlog();
        
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
