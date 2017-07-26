<?php

namespace CasperBounty\ToolsBundle\Controller;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\ProfilesBundle\Form\addProfile;
use CasperBounty\ToolsBundle\Entity\Tools;
use CasperBounty\ToolsBundle\Form\addToolForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Profiler\Profile;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $fmVal=new Tools();
        $form=$this->createForm(addToolForm::class,$fmVal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tool = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($tool);
            $em->flush();
        }

        $em = $this->getDoctrine()->getRepository('CasperBountyToolsBundle:Tools');
        $tools = $em->findAll();
        //echo count($tools);
        $profileObj=new Profiles();
        $profileForm=$this->createForm(addProfile::class,$profileObj,array('action'=>$this->generateUrl('casper_bounty_projects_targetsToProjectFromList',array('projectId'=>1))));
        //die();
        return $this->render('@CasperBountyTools/tools.html.twig', array(
            'form' => $form->createView(),
            'toolsArr' => $tools,
            'addProfileForm'=>$profileForm->createView()
        ));
    }
    public function runToolAction($id){
        $ts=$this->get('casper_bounty_tools.toolssrvice');
        $ts->runTool($id);
        
    }
    public function getHostsIpsAction($targetid){
        $ts=$this->get('casper_bounty_tools.toolssrvice');
        //$ts->runTool($id);
        $ts->getAllIps($targetid);

    }
}
