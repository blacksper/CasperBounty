<?php

namespace CasperBounty\TasksBundle\Controller;

use CasperBounty\ResultsBundle\Entity\Results;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CasperBountyTasksBundle:Default:index.html.twig');
    }

    public function addResultAction($taskId, Request $request){
        $result=new Results();
        $doctr=$this->getDoctrine();
        $task=$doctr->getRepository('CasperBountyTasksBundle:Tasks')->find($taskId);

        $resultsRepo=$doctr->getRepository('CasperBountyResultsBundle:Results');
        $resExist=$resultsRepo->findBy(array('taskid'=>$task));
        if($resExist)
            die('ayayay uze est');

        $resultData=$request->get('resultData');
        if(!$resultData)
            die('caramba');
        $result->setTaskid($task)->setResult($resultData);
        $doctr->getEntityManager()->persist($result);
        $doctr->getEntityManager()->flush();
        die();
        return 0;
    }
}
