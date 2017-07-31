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

class DefaultController extends Controller
{
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

    public function addAction()
    {

    }

    public function getTargetInfoAction($projectId,$targetId){
        $messageGenerator = $this->get('casper_bounty_targets.targetsservice');
        //$messageGenerator->getTargetIps(79);
        //die();
        $tarhost=array();
        $subTargets = $messageGenerator->getSubtargets($targetId);

        foreach ($subTargets as $target){
            $tarhost[]['target']=$target;
            $tarhost[]['ips']=$target->getIpid();
        }

        //echo count($subTargets[0]->getIpid());
        $targetInfo['subtargets']=$subTargets;
        return $this->render('@CasperBountyTargets/targetInfo.html.twig',array('projectId'=>$projectId,'targetId'=>$targetId,'targetInfo'=>$targetInfo));
    }
}
