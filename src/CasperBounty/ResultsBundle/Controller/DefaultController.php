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
    public function indexAction()
    {
        return $this->render('CasperBountyResultsBundle:Default:index.html.twig');
    }

    /**
     * @Route("/results/{taskid}/addResult", requirements={"taskid" = "\d+","methods"="POST"}, name="casper_bounty_task_add_result")
     * @Method({"POST"})
     */
    public function addResultAction($taskid, Request $request){
        $result=new Results();
        $doctr=$this->getDoctrine();
        $task=$doctr->getRepository('CasperBountyTasksBundle:Tasks')->find($taskid);

        $resultsRepo=$doctr->getRepository('CasperBountyResultsBundle:Results');
        $resExist=$resultsRepo->findBy(array('taskid'=>$task));
        if($resExist)
            die('ayayay uze est');

        $resultData=$request->get('resultData');
        if(!$resultData)
            die('caramba');
        $resultData=htmlentities(urldecode($resultData));

        $result->setTaskid($task)->setResult($resultData);
        $doctr->getEntityManager()->persist($result);
        $doctr->getEntityManager()->flush();
        die('horoshechno');
        return 0;

    }
}
