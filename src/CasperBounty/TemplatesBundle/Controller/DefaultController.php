<?php

namespace CasperBounty\TemplatesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyTemplatesBundle:Default:index.html.twig');
    }
}
