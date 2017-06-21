<?php

namespace CasperBounty\TasklistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TasklistBundle:Default:index.html.twig');
    }
}
