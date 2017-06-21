<?php

namespace CasperBounty\ResultsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyResultsBundle:Default:index.html.twig');
    }
}
