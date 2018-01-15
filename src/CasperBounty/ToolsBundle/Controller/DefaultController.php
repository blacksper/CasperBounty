<?php

namespace CasperBounty\ToolsBundle\Controller;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\ProfilesBundle\Form\addProfile;
use CasperBounty\ToolsBundle\Entity\Tools;
use CasperBounty\ToolsBundle\Form\addToolForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DefaultController extends Controller
{
    /**
     * @Route("/tools", name="casper_bounty_tools_homepage")
     */
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
        //$tools[0]->getToolid();

        //echo count($tools);
        $profileObj=new Profiles();
        $profileForm=$this->createForm(addProfile::class,$profileObj,array('action'=>$this->generateUrl('casper_bounty_profiles_addprofile')));//hardcoded id

        //$profileObj->getToolid()

        return $this->render('@CasperBountyTools/tools.html.twig', array(
            'form' => $form->createView(),
            'toolsArr' => $tools,
            'addProfileForm'=>$profileForm->createView()
        ));
    }

    /**
     * @Route("/tools/{profileId}/run", name="casper_bounty_tools_runtool")
     */
    public function runToolAction($profileId, Request $request){
        $targetsJson=$request->get('targetsArr');
        dump($request);
        //die();
        $ts=$this->get('casper_bounty_tools.toolssrvice');
        $targetsArr=explode(',',$targetsJson);

        $ts->runTool($profileId,$targetsArr);
        //die();
        $response=new Response();
        $response->setContent('runned');
        return $response;
    }

    /**
     * @Route("/project/{projectId}/tools/getips", name="casper_bounty_tools_getips")
     */
    public function getHostsIpsAction(Request $request,$projectId){
        //);
        $targetsArr=$request->get('targetsArr');
            $ts = $this->get('casper_bounty_tools.toolssrvice');
            //$ts->runTool($id);
            $ts->getAllIps($targetsArr,$projectId);

        return $this->redirectToRoute('casper_bounty_projectTargets',array('projectId'=>$projectId));
    }

    /**
     * @Route("/tools/addips", requirements={"targetid" = "\d+","methods"="POST"}, name="casper_bounty_tools_addips")
     * @Method({"POST"})
     */
    public function addIpsAction(Request $request){

        $test=$request->request->get('resultData');

        $targetsArray=json_decode($test,1,2048);
        $targetsService=$this->get('casper_bounty_targets.targetsservice');
        
        $response=new Response();
        $response->setContent('govnocod');

        if(!is_array($targetsArray))
            return $response;
        foreach ($targetsArray as $target) {
            print_r( $target);
            //$ipsArray=json_decode($target['data']['ipsData'],1);
            $targetsService->addIps(
                $target['targetId'],            //targetid
                $target['data']['ipsData'],     //ip adresses list
                $target['data']['projectId']    //project id
            );

        }

        //$response->sendContent();
        return $response;
    }

    /**
     * @Route("/tools/{toolId}/getProfiles", name="casper_bounty_tools_getprofiles")
     */
    public function getProfilesByToolid($toolId){
        $repP=$this->getDoctrine()->getRepository('CasperBountyProfilesBundle:Profiles');
        $profilesArr=$repP->findBy(array('toolid'=>3));
        //dump($profilesArr);
        $html=$this->renderView('@CasperBountyTools/ajaxGetProfiles.html.twig',array('profiles'=>$profilesArr));
        return new JsonResponse($html,200);
    }
}

