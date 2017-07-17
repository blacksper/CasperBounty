<?php

namespace CasperBounty\ToolsBundle\Controller;

use CasperBounty\ToolsBundle\Entity\Tools;
use CasperBounty\ToolsBundle\Form\addToolForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        return $this->render('@CasperBountyTools/tools.html.twig', array(
            'form' => $form->createView(),
            'toolsArr' => $tools
        ));
    }
    public function addToolAction(Request $request){

        
    }
}
