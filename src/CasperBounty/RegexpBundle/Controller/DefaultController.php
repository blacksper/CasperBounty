<?php

namespace CasperBounty\RegexpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyRegexpBundle:Default:index.html.twig');
    }
}
