<?php

namespace CasperBounty\ScenarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $scenArr=$this->getDoctrine()->getRepository('CasperBountyScenarioBundle:Scenario')->findAll();

        return $this->render('CasperBountyScenarioBundle:Default:scenarioTable.html.twig',array('scenArr'=>$scenArr));
    }
}
