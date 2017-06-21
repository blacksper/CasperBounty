<?php

namespace CasperBounty\GroupsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyGroupsBundle:Default:index.html.twig');
    }
}
