<?php

namespace CliBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CliBundle:Default:index.html.twig');
    }
}
