<?php

namespace Application\DefaultBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefectsController extends Controller
{    
    public function indexAction()
    {   
        return $this->render(
        'ApplicationDefaultBundle:Defects:index.html.twig'
        );
    }
}
