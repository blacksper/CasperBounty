<?php

namespace CasperBounty\TargetsBundle\Controller;

use CasperBounty\TargetsBundle\Entity\Targets;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class addTargetsController extends Controller
{

    /**
     * @Route("/projects/{projectId}/addtargets", name="casper_bounty_projects_addTargets")
     * @Method({"GET"})
     */
    public function indexAction($projectId)
    {

        return $this->render('@CasperBountyTargets/addTargets/addTargetsMain.html.twig',['projectId'=>$projectId]);
    }


    /**
     * @Route("/{/projects/{projectId}/addtargets", requirements={"projectId" = "\d+"}, defaults={"id" = 1}, name="casper_bounty_targets_addTargets_post")
     * @Method({"POST"})
     */
    public function addTargetsAction(int $projectId,Request $request)
    {
        $targetsText=$request->get('targets');
        $targetsService = $this->get('casper_bounty_targets.targetsservice');

        $targetsService->setProjectId($projectId);
        //die();
        $newTargetsCount=$targetsService->addHosts($targetsText);


        return $this->render('@CasperBountyTargets/addTargets/addTargetsMain.html.twig',array('projectId'=>$projectId,'newTargetsCount'=>count($newTargetsCount)));
    }

    /**
     * @Route("/projects/{projectId}/addtargetsfromfile", name="casper_bounty_targets_addTargets_fromFile")
     * @Method({"POST"})
     */
    public function addTargetsFromFileAction($projectId,Request $request)
    {
        $targetsFiles=$request->files->get('hostsFiles');

        if(!empty($targetsFiles)) {
            $targetsService = $this->get('casper_bounty_targets.targetsservice');
            $targetsService->setProjectId($projectId);
            $newTargetsCount = $targetsService->addHostsFromFile($targetsFiles);
            //dump($newTargetsCount);
        }



        //$targetsService = $this->get('casper_bounty_targets.targetsservice');
        //$newTargetsCount=$targetsService->addHosts($targetsText,$projectId);
        die();
        //return $this->render('@CasperBountyTargets/addTargets/addTargetsMain.html.twig',array('projectId'=>$projectId,'newTargetsCount'=>count($newTargetsCount)));
    }
}
