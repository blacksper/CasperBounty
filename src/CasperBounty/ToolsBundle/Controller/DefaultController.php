<?php

namespace CasperBounty\ToolsBundle\Controller;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\ProfilesBundle\Form\addProfile;
use CasperBounty\ToolsBundle\Entity\Tools;
use CasperBounty\ToolsBundle\Form\addToolForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        //echo count($tools);
        $profileObj=new Profiles();
        $profileForm=$this->createForm(addProfile::class,$profileObj,array('action'=>$this->generateUrl('casper_bounty_profiles_addprofile')));//hardcoded id

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
        $ts=$this->get('casper_bounty_tools.toolssrvice');
        $targetsArr=explode(',',$targetsJson);
        $ts->runTool($profileId,$targetsArr);
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
     * @Route("/tools/{targetid}/addips", requirements={"targetid" = "\d+","methods"="POST"}, name="casper_bounty_tools_addips")
     */
    public function addIpsAction($targetid, Request $request){
        //TODO: добавить проверку на дубликаты
        //dump($request);
        $stringIpsData=$request->request->get('ipsData');
        $projectId=$request->request->get('projectId');
        //echo $stringIpsData;
        //die('die mutherfucker');
        $ipsArray=json_decode($stringIpsData);
        print_r($ipsArray);
        //die();
        $targetsService=$this->get('casper_bounty_targets.targetsservice');
        $targetsService->addIps($targetid,$ipsArray,$projectId);
        $response=new Response();
        $response->setContent('govnocod');
        //$response->sendContent();
        return $response;
    }
}
