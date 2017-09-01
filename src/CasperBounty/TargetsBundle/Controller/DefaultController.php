<?php

namespace CasperBounty\TargetsBundle\Controller;

use CasperBounty\TargetsBundle\Entity\Targets;
use CasperBounty\TargetsBundle\Form\FirstForm;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpKernel\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/targets", name="casper_bounty_targets_homepage")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $successAdded=array();
        $fmVal=new Targets();
        $form = $this->createForm(FirstForm::class,$fmVal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $hostsSting = $form['host']->getData();
            $hostsArray=explode("\r\n",$hostsSting);

            foreach ($hostsArray as $host) {
                $existHost = $em->getRepository('CasperBountyTargetsBundle:Targets')->findOneBy(array('host' => $host));
                if (!$existHost) {
                    $target=new Targets();
                    $target->setType('domain');
                    $target->setHost($host);
                    $em->persist($target);
                    $successAdded[]=$host;
                }
            }

            $em->flush();

            //return $this->redirectToRoute('casper_bounty_targets_homepage');
            return $this->render('CasperBountyTargetsBundle:Default:index.html.twig',
                array(
                    'success'=>$successAdded,
                    'form' => $form->createView())
            );

        }


        return $this->render('CasperBountyTargetsBundle:Default:index.html.twig', array('form' => $form->createView()));
    }


    /**
     * @Route("/projects/{projectId}/targets/{targetId}", name="casper_bounty_targets_targetid")
     * @Method({"GET","POST"})
     */
    public function getTargetInfoAction($projectId,$targetId){
        $messageGenerator = $this->get('casper_bounty_targets.targetsservice');
        $doctr=$this->getDoctrine();
        $tarhost=array();
        $subTargets = $messageGenerator->getSubtargets($targetId);
       // $messageGenerator->checkIpExist('199.16.156.9',1);

        if(!empty($subTargets))
        foreach ($subTargets as $target){
            $tarhost[]['target']=$target;
            $tarhost[]['ips']=$target->getIpid();
        }

        $maintarget=$doctr->getRepository('CasperBountyTargetsBundle:Targets')->createQueryBuilder('t')->where('t.targetid=:targetid')
            ->setParameter('targetid',$targetId)
            ->getQuery()
            ->getResult();
        $maintarget=$maintarget[0];

        $targetInfo['subtargets']=$subTargets;
        $profiles=$doctr->getRepository('CasperBountyProfilesBundle:Profiles')->findAll();
        return $this->render('@CasperBountyTargets/targetInfo.html.twig',array('projectId'=>$projectId,'targetId'=>$targetId,'targetInfo'=>$targetInfo,'maintarget'=>$maintarget,'profiles'=>$profiles));
    }

    /**
     * @Route("/projects/{projectId}/ip/{targetId}", name="casper_bounty_domainsByIp")
     * @Method({"GET","POST"})
     */
    public function getDomainsByIp($targetId,$projectId){
        $repos=$this->getDoctrine()->getRepository('CasperBountyTargetsBundle:Targets');
        $target=$repos->find($targetId);
        //echo $targetId;
        if($target->getType()!='ip'){
            return $this->redirectToRoute('casper_bounty_projectTargets',array('projectId'=>$projectId));
        }

        $ips=$repos->createQueryBuilder('d');
        $query=$ips->innerJoin('d.ipid','ipid')->where('ipid.targetid=:tarid')
            ->getDQL();
        //dump($query);
        $res=$this->getDoctrine()->getManager()->createQuery($query)->setParameter('tarid',$targetId)->getResult();
        //dump($res);
        //die();
        return $this->render('@CasperBountyTargets/ipView/domainByIp.html.twig',array('domains'=>$res,'projectId'=>$projectId));
    }
}
