<?php

namespace CasperBounty\ConditionsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyConditionsBundle:Default:index.html.twig');
    }
}
