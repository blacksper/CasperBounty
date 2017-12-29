<?php

namespace CasperBounty\ProjectsBundle\Controller;


use CasperBounty\ProjectsBundle\Form\AddProject;
use CasperBounty\TargetsBundle\Entity\Targets;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(AddProject::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectName = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($projectName);
            $em->flush();
            $id = $projectName->getProjectid(); //TODO рассмиотреть надо ли оно вообще тут

            //return $this->redirectToRoute('casper_bounty_projects_id',array('projectId'=>$id));
        }
        $em = $this->getDoctrine()->getRepository('CasperBountyProjectsBundle:Projects');
        $projects = $em->findAll();

        return $this->render('CasperBountyProjectsBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
            'projects' => $projects
        ));
    }

    public function showTargetsAction($projectId)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('CasperBountyTargetsBundle:Targets');
        $query = $rep->createQueryBuilder('t')->select('t,(select count(t2.targetid) from CasperBounty\TargetsBundle\Entity\Targets t2 where t2.parentid =t.targetid)')->leftJoin('t.projectid', 'p')
            ->where('p.projectid=:pid and t.type=\'maindomain\'')
            ->groupBy('t.host')
            ->setParameter('pid', $projectId)
            ->getQuery();
        //$sq=$query->getSQL();
        //echo $sq;
        $targets = $query->getResult();
        //dump($targets);
        //echo count($targets);
        $targetsRes = array();
        foreach ($targets as $target) {
            $targetsRes[] = array('id' => $target[0]->getTargetid(),'type'=>$target[1], 'hostName' => $target[0]->getHost());
        }


        return $this->render('@CasperBountyProjects/showTargets.html.twig', array('targets' => $targetsRes, 'projectId' => $projectId));

    }

    public function showAction($projectId)
    {
        return $this->render('@CasperBountyProjects/sin.html.twig', array('projectId' => $projectId));
    }



    //обработка пост запроса к роуту /addtargeets
    public function targetsToProjectAction(Request $request)
    {
        $formData = $request->get('form');
        //print_r($request->get('form'));
        $targetsToProject = $this->get('casper_bounty_projects.testservice');
        $targetsArr = $formData['targetid'];
        $projectId = $formData['projectId'];

        $targetsToProject->addTargetsToProject($projectId, $targetsArr);

        return $this->redirectToRoute('casper_bounty_projects_addTargets', array('projectId' => $projectId));
    }

    public function targetsToProjectFromListAction(Request $request,$projectId)
    {

        $formData = $request->get('first_form');
        $hostsString = $formData['host'];
        $hostsArray = explode("\r\n", $hostsString);
        $targetsService=$this->get('casper_bounty_targets.targetsService');
        $targetsService->projectId=$projectId;

        $successAdded=$targetsService->addHosts($hostsArray);
        //$targetsToProject = $this->get('casper_bounty_projects.testservice');
        //$targetsToProject->addTargetsToProject(1, $successAdded);


        return $this->redirectToRoute('casper_bounty_projectTargets', array('projectId' => $projectId));
    }

    public function createAction()
    {


    }
}
