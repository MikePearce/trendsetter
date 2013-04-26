<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction($name)
    {
        $twitterClient = $this->container->get('guzzle.twitter.client');
        $status = $twitterClient->get('statuses/user_timeline.json')
             ->send()->getBody();
 
        return $this->render('AppBundle:Default:index.html.twig', array(
            'status' => $status
        ));
    }
    
    public function aindexAction()
    {

        return $this->render('ApplicationDefaultBundle:Default:index.html.twig');
    }
}
