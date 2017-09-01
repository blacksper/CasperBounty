<?php

namespace CasperBounty\TasksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyTasksBundle:Default:index.html.twig');
    }
}
