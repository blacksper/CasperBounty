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
        $query = $rep->createQueryBuilder('t')->leftJoin('t.projectid', 'p')->where('p.projectid=:pid')->setParameter('pid', $projectId)->getQuery();
        $targets = $query->getResult();
        $targetsRes = array();
        foreach ($targets as $target) {
            $targetsRes[] = array('id' => $target->getTargetid(),'type'=>$target->getType(), 'hostName' => $target->getHost());
        }


        return $this->render('@CasperBountyProjects/showTargets.html.twig', array('targets' => $targetsRes, 'projectId' => $projectId));

    }

    public function showAction($projectId)
    {
        return $this->render('@CasperBountyProjects/sin.html.twig', array('projectId' => $projectId));
    }

    public function addTargetsAction($projectId)
    {
        $target = new Targets();
        $form = $this->createFormBuilder($target);
        $form2 = $this->createForm(
            'CasperBounty\TargetsBundle\Form\FirstForm',
            null,
            array(
                'action' => $this->generateUrl(
                    'casper_bounty_projects_targetsToProjectFromList', array('projectId' => $projectId))
            )
        );

        $em = $this->getDoctrine()->getRepository('CasperBountyTargetsBundle:Targets');
        $qwe = $em->createQueryBuilder('t')->select('t')->leftJoin('t.projectid', 'p')->where('p.projectid is null')->getQuery();

        $allTargets = $qwe->getResult();
        $hosts = array();

        foreach ($allTargets as $host)
            $hosts[$host->getHost()] = $host->getTargetid();

        $form = $form
            ->add('targetid', ChoiceType::class, array(
                'choices' => $hosts,
                'multiple' => true,
                'label' => false,
                //'attr'=>array('id'=>'example-getting-started')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Select',
                'attr' => array('class' => 'btn btn-sm btn-success')))
            ->add('projectId', HiddenType::class, array(
                'data' => $projectId))

            ->getForm();
        $messageGenerator = $this->get('casper_bounty_projects.testservice');
        $message = $messageGenerator->getHappyMessage();

        return $this->render('@CasperBountyProjects/addTargets.html.twig',
            array('projectId' => $projectId,
                'hosts' => $hosts,
                'form' => $form->createView(),
                'form2' => $form2->createView(),
                'mess' => $message
            )
        );
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

    public function targetsToProjectFromListAction(Request $request)
    {
        $formData = $request->get('first_form');
        $hostsString = $formData['host'];
        $projectId = 1;
        $hostsArray = explode("\r\n", $hostsString);
        $targetsService=$this->get('casper_bounty_targets.targetsService');
        //$targetsService->isMainDomain($hostsArray);
        $successAdded=$targetsService->addHosts($hostsArray);
        $targetsToProject = $this->get('casper_bounty_projects.testservice');
        $targetsToProject->addTargetsToProject(1, $successAdded);


        return $this->redirectToRoute('casper_bounty_projects_id_get_targets', array('projectId' => $projectId));
    }

    public function createAction()
    {


    }
}
