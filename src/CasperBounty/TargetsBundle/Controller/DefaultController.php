<?php

namespace CasperBounty\TargetsBundle\Controller;

use CasperBounty\TargetsBundle\Entity\Targets;
use CasperBounty\TargetsBundle\Form\FirstForm;
use Doctrine\DBAL\Types\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
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
     * @Route("/projects/{projectId}/targets/{targetId}", requirements={"targetId"="\d+","projectId"="\d+"}, name="casper_bounty_targets_targetid")
     * @Method({"GET","POST"})
     */
    public function getTargetInfoAction(int $projectId,int $targetId){
        $targetsService = $this->get('casper_bounty_targets.targetsservice');
        $doctr=$this->getDoctrine();
        //$tarhost=array();
        $targetsService->setProjectId($projectId);
        $subTargets = $targetsService->getSubtargets($targetId);

       // $messageGenerator->checkIpExist('199.16.156.9',1);
        dump($subTargets);
       // if(!empty($subTargets))
//        foreach ($subTargets as $target){
//            $tarhost[]['target']=$target;
//            $tarhost[]['ips']=$target->getIpid();
//        }

        $targetsRepo=$doctr->getRepository('CasperBountyTargetsBundle:Targets');

        $maintarget=$targetsRepo->createQueryBuilder('t')->where('t.targetid=:targetid')->andWhere('t.type=:domaintype')
            ->setParameter('targetid',$targetId)
            ->setParameter('domaintype',"maindomain")
            ->getQuery()
            ->getResult();
        if($maintarget)
            $maintarget=$maintarget[0];
        else
            return $this->render('@CasperBountyTargets/targetInfo.html.twig',
                array('projectId'=>$projectId,
                    'profiles'=>array(),
                    'targetId'=>$targetId,

                    'errorMsg'=>'Hmm, id is not maintarget'));

        $targetInfo['subtargets']=$subTargets;
        $profiles=$doctr->getRepository('CasperBountyProfilesBundle:Profiles')->findAll();


        $group=$targetsRepo->createQueryBuilder('t')
            ->select('count(t.targetid) cnt,ipid.host,ipid.targetid')
            ->innerJoin('t.ipid','ipid')
            ->innerJoin('t.projectid','proj')
            ->where('proj.projectid=:projectid')
            ->groupBy('ipid.host')
            ->orderBy('cnt','DESC')
            ->setParameters(array('projectid'=>$projectId))
            ->getQuery()
            ->getResult();
        //dump($heh);
        //die();

//        $query=$ips->innerJoin('d.ipid','ipid')->where('ipid.targetid=:tarid')
//            ->getDQL();
        //dump()
        //dump($results);
        return $this->render('@CasperBountyTargets/targetInfo.html.twig',
            array(
                'projectId'=>$projectId,
                //'targetId'=>$targetId,
                'targetInfo'=>$targetInfo,
                'maintarget'=>$maintarget,
                'profiles'=>$profiles,
                //'results'=>$results,
                'group'=>$group
            ));

    }

    /**
     * @Route("/projects/{projectId}/ip/{targetId}", requirements={"targetId"="\d+","projectId"="\d+"}, name="casper_bounty_domainsByIp")
     * @Method({"GET","POST"})
     */
    public function getDomainsByIp(int $targetId,int $projectId){
        $doctrine=$this->getDoctrine();
        $repos=$doctrine->getRepository('CasperBountyTargetsBundle:Targets');
        //$target=$repos->find($targetId);
        //$target=$repos->findBy(array('targetid'=>$targetId,'projectid'=>$projectId));

//        $target=$repos->createQueryBuilder('t')
//            ->select('t')
//            ->innerJoin('t.projectid','p')
//            ->where('t.targetid=:targetid')
//            ->andWhere('p.projectid=:projectid')
//            ->setParameters(array('targetid'=>$targetId,'projectid'=>$projectId))
//        ->getQuery()->getResult();

        $target=$repos->find($targetId);
        $domains=$target->getDomainid();
        //$domains[0]->
        dump($domains[0]->getServiceid());
        //if(empty($target))
        //    return $this->redirectToRoute('casper_bounty_projectTargets',array('projectId'=>$projectId));
        //dump($target);
        //die();

//        if($target[0]->getType()!='ip'){
//            return $this->redirectToRoute('casper_bounty_projectTargets',array('projectId'=>$projectId));
//        }

        //$ips=$repos->createQueryBuilder('d');
        //$query=$ips->innerJoin('d.ipid','ipid')->where('ipid.targetid=:tarid')
        //    ->getDQL();
        //dump($query);
        //$res=$doctrine->getManager()->createQuery($query)->setParameter('tarid',$targetId)->getResult();
        //dump($res);
        //die();
        return $this->render('@CasperBountyTargets/ipView/domainByIp.html.twig',array('domains'=>$domains,'projectId'=>$projectId));
    }

    /**
     * @Route("/projects/{projectId}/target/{targetId}/info", requirements={"targetId"="\d+","projectId"="\d+"}, name="casper_bounty_detTargetAction")
     * @Method({"GET","POST"})
     */
    public function detailedTargetInfoAction(int $targetId, int $projectId){

        $servicesRepo=$this->getDoctrine()->getRepository('CasperBountyServicesBundle:Services');
        $services=$servicesRepo->findBy(array('targetid'=>$targetId));
        //$project=$this->getDoctrine()->getRepository('CasperBountyProjectsBundle:Projects')->find($projectId);
        dump($services);
        return $this->render('@CasperBountyTargets/info/detailedTargetInfo.html.twig',array('services'=>$services,'projectId'=>$projectId));
    }

}
