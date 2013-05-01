<?php

namespace Application\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\DefaultBundle\Lib\Estimates;
use Symfony\Component\HttpFoundation\JsonResponse;

class StoriesController extends Controller
{    
    public function indexAction()
    {

        return $this->render(
            'ApplicationDefaultBundle:Stories:index.html.twig', 
            array('stuff' => 'stuff')
        );
    }

    public function dataAction($type = 'totalstoriespermonth') {
      
      $easybacklogClient = $this->get('mikepearce_easybacklog_api');
      $easybacklogClient->setAccountId('477')
                        ->setBacklog(array(9248,7869,9555));
      
      $estimates = new Estimates($easybacklogClient);
      
      switch($type) {
        case 'totalstoriespermonth':
          $data = $estimates->gettotalStoriesPerMonth();
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
