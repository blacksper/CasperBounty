<?php

namespace CasperBounty\ProfilesBundle\Controller;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\ProfilesBundle\Form\addProfile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyProfilesBundle:Default:index.html.twig');
    }

    public function addProfileAction(Request $request)
    {

        $fmVal = new Profiles();
        $form = $this->createForm(addProfile::class,$fmVal);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);

            $em = $this->getDoctrine()->getManager();

            $toolId = $form['toolId']->getData()->getToolid();
            $name = $form['name']->getData();
            $cmdpath = $form['cmd']->getData();
            echo $toolId;

            $tool = $em->getRepository('CasperBountyToolsBundle:Tools')->find($toolId);
            //die();
            if (!$tool)
                return 0;
            $profile = new Profiles();
            $profile->setToolid($tool);
            $profile->setName($name);
            $profile->setCmd($cmdpath);
            $em->persist($profile);
            //$successAdded[] = $host;
            $em->flush();

            //return $this->redirectToRoute('casper_bounty_targets_homepage');
//            return $this->render('CasperBountyTargetsBundle:Default:index.html.twig',
//                array(
//                    'success' => $successAdded,
//                    'form' => $form->createView())
//            );
            return $this->redirectToRoute('casper_bounty_tools_homepage');

        }


        //dump($request);
        //die();

        //$pService=$this->get('casper_bounty_profiles.profileservice');
        //$pService->addProfile($toolId);

        return 0;
    }
}
