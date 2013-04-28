<?php

namespace Application\TrendsetterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationTrendsetterBundle:Default:index.html.twig', array('name' => $name));
    }
}
