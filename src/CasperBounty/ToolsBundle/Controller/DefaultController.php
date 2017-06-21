<?php

namespace CasperBounty\ToolsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyToolsBundle:Default:index.html.twig');
    }
}
