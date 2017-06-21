<?php

namespace CasperBounty\ProjectsBundle\Controller;

use CasperBounty\ProjectsBundle\Entity\Projects;
use CasperBounty\ProjectsBundle\Form\AddProject;
//use CasperBounty\TargetsBundle\Form\addTargetsForm;
use CasperBounty\ProjectsBundle\Form\addTargetsForm;
use CasperBounty\ProjectsBundle\Service\Testservice1;
use CasperBounty\TargetsBundle\Entity\Targets;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use CasperBounty\TargetsBundle\Form\FirstForm;
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
        $form2=$this->createForm(
            'CasperBounty\TargetsBundle\Form\FirstForm',
            null,
            array(
                'action'=>$this->generateUrl(
                    'casper_bounty_projects_targetsToProjectFromList', array('projectId'=>$projectId))
            )
        );
        //$form2->

        $em = $this->getDoctrine()->getRepository('CasperBountyTargetsBundle:Targets');
        $qwe=$em->createQueryBuilder('t')->select('t')->leftJoin('t.projectid','p')->where('p.projectid is null')->getQuery();

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
        $messageGenerator=$this->get('testservice');
        $message = $messageGenerator->getHappyMessage();

        //return $this->redirectToRoute('casper_bounty_projects_id',array('projectId'=>$projectId));

        return $this->render('@CasperBountyProjects/addTargets.html.twig',
            array('projectId'=>$projectId,
                'hosts'=>$hosts,
                'form'=>$form->createView(),
                'form2'=>$form2->createView(),
                'mess'=>$message
            )
        );
    }

    //обработка пост запроса к роуту /addtargeets
    public function targetsToProjectAction(Request $request){
        $formData=$request->get('form');
        print_r($request->get('form'));

        $targetsArr=$formData['targetid'];
        $projectId=$formData['projectId'];

        $em = $this->getDoctrine()->getManager();
        $project=$em->getRepository('CasperBountyProjectsBundle:Projects')->find($projectId);
        $targets=$em->getRepository('CasperBountyTargetsBundle:Targets')->findBy(array('targetid' => $targetsArr));


        foreach ($targets as $target)
            $project->addTargetid($target);

        $em->flush();

    return $this->redirectToRoute('casper_bounty_projects_addTargets',array('projectId'=>$projectId));
    }

    public function targetsToProjectFromListAction(Request $request){
        $successAdded=array();
        $formData=$request->get('first_form');
        $hostsString=$formData['host'];
        //print_r($hosts);

        $projectId=$formData['projectId'];
        $fmVal=new Targets();

            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            //$task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            //$hostsSting = $form['host']->getData();
            $hostsArray=explode("\r\n",$hostsString);
            //var_dump($hostsArray);
            //die();
            foreach ($hostsArray as $host) {
                $existHost = $em->getRepository('CasperBountyTargetsBundle:Targets')->findOneBy(array('host' => $host));
                //$existHost = $em->getRepository('CasperBountyTargetsBundle:Targets')
                 //   ->createQueryBuilder('t')->andWhere('t.host not in (:hosts)')->setParameter('hosts',$hostsSting);
                if (!$existHost) {
                    $target=new Targets();
                    $target->setType('domain');
                    $target->setHost($host);
                    $em->persist($target);
                    $successAdded[]=$host;
                }

// else {
//                //throw new Exception\NotFoundHttpException('No product found for id ' . $host);
////                throw $this->ex(
////                    'No product found for id ' . $host
////                );
//                //$this->createException();
//                throw new Exception\HttpException(404,$host);
//
//            }
            $em->flush();

            //return $this->redirectToRoute('casper_bounty_targets_homepage');
//            return $this->render('CasperBountyTargetsBundle:Default:index.html.twig',
//                array(
//                    'success'=>$successAdded,
//                    'form' => $form->createView())
//            );
                return $this->redirectToRoute('casper_bounty_projects_targetsToProject',array('projectId'=>$projectId));

        }
    }

    public function createAction(){



    }
}
