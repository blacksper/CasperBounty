<?php

namespace CasperBounty\ProfilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyProfilesBundle:Default:index.html.twig');
    }
}
