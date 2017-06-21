<?php

namespace CasperBounty\ProjectsBundle\Controller;

use CasperBounty\ProjectsBundle\Entity\Projects;
use CasperBounty\ProjectsBundle\Form\AddProject;
//use CasperBounty\TargetsBundle\Form\addTargetsForm;
use CasperBounty\ProjectsBundle\Form\addTargetsForm;
use CasperBounty\TargetsBundle\Entity\Targets;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form=$this->createForm(AddProject::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectName = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($projectName);
            $em->flush();
            $id=$projectName->getProjectid();

            return $this->redirectToRoute('casper_bounty_projects_id',array('projectId'=>$id));
        }


        return $this->render('CasperBountyProjectsBundle:Default:index.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    public function showTargetsAction($projectId){
        $em = $this->getDoctrine()->getManager();
        $rep=$em->getRepository('CasperBountyTargetsBundle:Targets');
        $query=$rep->createQueryBuilder('t')->leftJoin('t.projectid','p')->where('p.projectid=:pid')->setParameter('pid',$projectId)->getQuery();
        $targets=$query->getResult();
        $targetsRes=array();
        foreach ($targets as $target){
            $targetsRes[]=array('id'=>$target->getTargetid(),'hostName'=>$target->getHost());
        }


        return $this->render('@CasperBountyProjects/showTargets.html.twig',array('targets'=>$targetsRes,'projectId'=>$projectId));

    }

    public function showAction($projectId){
        return $this->render('@CasperBountyProjects/sin.html.twig',array('projectId'=>$projectId));
    }

    public function addTargetsAction($projectId){
        $target=new Targets();
        $form=$this->createFormBuilder($target);

        $em = $this->getDoctrine()->getRepository('CasperBountyTargetsBundle:Targets');
        $qwe=$em->createQueryBuilder('t')->select('t')->leftJoin('t.projectid','p')->where('p.projectid is null')->getQuery();
        //echo $qwe;
        //$qwe1=$qwe->getResult();
        //echo $qwe->getSql();
        $allTargets=$qwe->getResult();
        $hosts=array();

        foreach ($allTargets as $host)
            $hosts[$host->getHost()]=$host->getTargetid();

        $form=$form
            ->add('targetid', ChoiceType::class, array(
            'choices'  => $hosts,
            'multiple'=>true,
            'label' => false,
            //'attr'=>array('id'=>'example-getting-started')
            ))
            ->add('save',SubmitType::class,array(
                'label'  => 'Select',
                'attr'=>array('class'=>'btn btn-sm btn-success')))
            ->add('projectId',HiddenType::class,array(
                'data'=>$projectId))
//            ->add('name')
//            ->add('tags', 'collection', array(
//                'type' => new TagType(),
//                'allow_add' => true,
//                'prototype' => true,
//                // Post update
//                'by_reference' => false,
//            ))
            ->getForm();

        return $this->render('@CasperBountyProjects/addTargets.html.twig',array('projectId'=>$projectId,'hosts'=>$hosts,'form'=>$form->createView()));
    }

    public function targetsToProjectAction(Request $request){
        $formData=$request->get('form');
        $targetsArr=$formData['targetid'];
        $projectId=$formData['projectId'];
        print_r($targetsArr);
        print_r($projectId);

        //$test=new Targets();
        //$test->addProjectid($projectId);


        $em = $this->getDoctrine()->getManager();
        $project=$em->getRepository('CasperBountyProjectsBundle:Projects')->find($projectId);
        $targets=$em->getRepository('CasperBountyTargetsBundle:Targets')->findBy(array('targetid' => $targetsArr));
        //$targets[]=$em->getRepository('CasperBountyTargetsBundle:Targets')->find($targetsArr[1]);
        //echo count($targets);

        foreach ($targets as $target) {
            $project->addTargetid($target);
        }

        $em->flush();
        //print_r($project);
    //die();
    return $this->redirectToRoute('casper_bounty_projects_addTargets',array('projectId'=>$projectId));
    }


    public function createAction(){



    }
}
