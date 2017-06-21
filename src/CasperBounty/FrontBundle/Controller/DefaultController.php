<?php

namespace CasperBounty\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyFrontBundle:Default:index.html.twig');
    }
}
