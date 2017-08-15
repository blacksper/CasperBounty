<?php

namespace CasperBounty\ScenarioBundle\Controller;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\ScenarioBundle\Entity\Scenariotoprofile;
use CasperBounty\ServicesBundle\Form\profileToService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $scenArr = $this->getDoctrine()->getRepository('CasperBountyScenarioBundle:Scenario')->findAll();
        $scenArr2 = $this->getDoctrine()->getRepository('CasperBountyScenarioBundle:Scenariotoprofile');
        //$scenArr2[0]->getProfileid()->getName();
        $arras=array();
        foreach ($scenArr as $scenario){
            $arras[]=array('self'=>$scenario,'profiles'=>$scenArr2->findBy(array('scenarioid'=>$scenario->getScenarioid())));
          //  echo $scenario->getScenarioid()."<br>";
        }

        //echo count($arras);
        dump($arras);
        //die();

        //echo count($scenArr2);
        //die();
        $fmVal = new Profiles();
        $form = $this->createForm(profileToService::class, $fmVal);
        //$scenArr[0]->
        return $this->render('CasperBountyScenarioBundle:Default:scenarioTable.html.twig', array('scenArr' => $scenArr,'arras'=>$arras, 'formProfiles' => $form->createView()));
    }


    public function scenarioInfoAction($scenarioId, Request $request)
    {
        $scenArr = $this->getDoctrine()->getRepository('CasperBountyScenarioBundle:Scenario')->findAll();


        $form = $this->createForm(profileToService::class, null, array(
            'action' => $this->generateUrl('casper_bounty_scenario_profiletoscenario', array('scenarioId' => $scenarioId))
        ));

        return $this->render('CasperBountyScenarioBundle:Default:scenarioInfo.html.twig', array('scenArr' => $scenArr, 'formProfiles' => $form->createView()));
    }


    public function addProfileToScenarioAction($scenarioId, Request $request)
    {
        if (!$scenarioId)
            return 0;
        $formData = $request->get('casper_bounty_services_profileToScenario');
        //print_r($request->get('form'));
        //dump($request);
        //dump($formData);

        $em = $this->getDoctrine()->getManager();
        $profilesIdArr = $formData['profileId'];
        dump($profilesIdArr);
        $repP = $this->getDoctrine()->getRepository('CasperBountyProfilesBundle:Profiles');

        //dump($profileArr);
        $repS = $this->getDoctrine()->getRepository('CasperBountyScenarioBundle:Scenario');
        $scenario=$repS->find($scenarioId);

        //dump($repP);
        foreach ($profilesIdArr as $profileId) {
            $profile = $repP->find($profileId);
            $sctp = new Scenariotoprofile();
            $sctp->setProfileid($profile);
            $sctp->setScenarioid($scenario);
            $em->persist($sctp);
        }

        $em->flush();
        //$targetsToProject = $this->get('casper_bounty_projects.testservice');
        //$targetsArr = $formData['targetid'];
        //$projectId = $formData['projectId'];


        //die();
        return $this->redirectToRoute('casper_bounty_scenario_info', array('scenarioId' => $scenarioId));
    }
}
