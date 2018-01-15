<?php

namespace CasperBounty\ResultsBundle\Controller;

use CasperBounty\ResultsBundle\Entity\Results;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/projects/{projectId}/results", name="casper_bounty_results_main")
     */

    public function indexAction($projectId)
    {
        $repoR = $this->getDoctrine()->getRepository('CasperBountyResultsBundle:Results');
        $results = $repoR->createQueryBuilder('r')->innerJoin('r.taskid', 't')
            ->innerJoin('t.targetid', 'tar')
            ->innerJoin('tar.projectid', 'p')
            ->where('p.projectid=:prid')
            ->orderBy('t.timestart','desc')
            ->setParameter('prid', $projectId)
            ->getQuery()
            ->getResult();
//        $results=$doctr
//            ->getRepository('CasperBountyResultsBundle:Results')
//            ->createQueryBuilder('r')->innerJoin('r.taskid','t')->where('t.targetid=:targetid')
//            ->setParameter('projectid',$projectId)
//            ->getQuery()
//            ->getResult();
        return $this->render('CasperBountyResultsBundle:Default:index.html.twig', array('results' => $results, 'projectId' => $projectId));
    }

    /**
     * @Route("/results/{taskid}/addResult", requirements={"taskid" = "\d+","methods"="POST"}, name="casper_bounty_task_add_result")
     * @Method({"POST"})
     */
    public function addResultAction($taskid, Request $request)
    {
        $resultsService=$this->get('casper_bounty_targets.resultsservice');
        $resultsService->setTaskId($taskid);
        $resultsService->setTarget();

        $fileOutput=$request->get('fileOutput');
        $resultData=$request->get('resultData');
        $resultsService->resultsHandle($resultData,$fileOutput);



        $doctr = $this->getDoctrine();
        $task = $doctr->getRepository('CasperBountyTasksBundle:Tasks')->find($taskid);

        $profile = $task->getProfileid();
        $softName = $profile->getToolId()->getName();
        $option = $profile->getCmd();
        dump($option);



        die();

        return 0;
    }

    /**
     * @Route("/results/{taskid}/prichesat", name="casper_bounty_task_prichesat")
     * @Method({"POST","GET"})
     */
    public function startRegexp($taskid, Request $request)
    {
        dump($request->get('regexpname'));
        dump($request->get('regexp'));
        $repoR = $this->getDoctrine()->getRepository('CasperBountyResultsBundle:Results');
        $res = $repoR->findBy(array('taskid' => $taskid));
        preg_match_all('#([0-9]{1,6})\/(tcp|udp)\s+(open|close|filtred)\s+([^\r\n]+)#', $res[0]->getResult(), $m);
        dump($res);
        dump($m);
        die();

    }
}
