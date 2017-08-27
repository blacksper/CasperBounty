<?php

namespace CasperBounty\ServicesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyServicesBundle:Default:index.html.twig');
    }
}
