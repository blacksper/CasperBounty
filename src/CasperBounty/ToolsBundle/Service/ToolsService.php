<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 21.06.2017
 * Time: 13:14
 */

namespace CasperBounty\ToolsBundle\Service;
//use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bridge\Doctrine

use Doctrine\ORM\EntityManager;

class ToolsService
{
    //private $em;
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function runTool($id){
        $rep=$this->entityManager->getRepository('CasperBountyToolsBundle:Tools');
        $t=$rep->find($id);
        echo $t->getCmdpath();
        die();
        //system('');
    }

    public function getAllIps($targetid){
        $repository=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        $t=$repository->find($targetid);
        $targetHost=$t->getHost();
        $target=sprintf('%s',$targetHost);
        $command="node D:\\njs\\nn\\resol.js --host=$target --targetid=$targetid";
        //$qwe=shell_exec($command);
        //echo $qwe;

        $ooo=new \COM('WScript.Shell');
        $ooo->Run($command,0,1);
        //dump($ooo);
        ///$output =$rr->StdOut->ReadAll;
        //echo $output;
        die();
    }

//    public function getHappyMessage()
//    {
//        $messages = [
//            'You did it! You updated the system! Amazing!',
//            'That was one of the coolest updates I\'ve seen all day!',
//            'Great work! Keep going!',
//        ];
//
//        $index = array_rand($messages);
//        //die('die motherfucker1');
//        return $messages[$index];
//    }

//    public function addTargetsToProject($projectId,$targetsArr){
//
//
//        $em=$this->entityManager;
//        $repositoryProjects=$em->getRepository('CasperBountyProjectsBundle:Projects');
//        $repositoryTargets=$em->getRepository('CasperBountyTargetsBundle:Targets');
//
//        $project=$repositoryProjects->find($projectId);
//        $targets=$repositoryTargets->findBy(array('targetid' => $targetsArr));
//        echo count($targets);
//        //die();
//        foreach ($targets as $target) {
//            $project->addTargetid($target);
//
//        }
//        $em->flush();
//        // die();
//
//
//    }
}