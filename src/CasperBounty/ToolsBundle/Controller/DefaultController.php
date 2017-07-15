<?php

namespace CasperBounty\ToolsBundle\Controller;

use CasperBounty\ToolsBundle\Entity\Tools;
use CasperBounty\ToolsBundle\Form\addToolForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $fmVal=new Tools();
        $form=$this->createForm(addToolForm::class,$fmVal)->createView();
        return $this->render('CasperBountyToolsBundle:Default:index.html.twig',array('form'=>$form));
    }
}
