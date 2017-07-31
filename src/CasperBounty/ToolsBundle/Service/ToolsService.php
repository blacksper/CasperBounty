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
use Symfony\Component\Process\Process;

class ToolsService
{
    //private $em;
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function buildCommand($id){
        $repT=$this->entityManager->getRepository('CasperBountyToolsBundle:Tools');
        $repP=$this->entityManager->getRepository('CasperBountyProfilesBundle:Profiles');
        $qb=$repP->find($id);
        //$qb->select('t')

        //echo $qb->getToolid()->getCmdpath();
        //echo $qb->getCmd();
        $targetHost='twitter.com';
        $toolPath=$qb->getToolid()->getCmdpath();
        $toolParams=$qb->getCmd();
        $cmd="\"$toolPath\" $toolParams ".$targetHost;
        //echo $cmd;
        //$t=$repT->find($id);
        //echo $t->getCmdpath();
       return $cmd;
        //system('');
    }

    public function getAllIps($targetid){
        $repository=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        $t=$repository->find($targetid);
        if(!$t)
            return 0;
        $targetHost=$t->getHost();

        $target=sprintf('%s',$targetHost);
        $command="node D:\\njs\\nn\\resol.js --host=$target --targetid=$targetid";


        $ooo=new \COM('WScript.Shell');
        $ooo->Run($command,7,0);

//        if (substr(php_uname(), 0, 7) == "Windows"){//working
//            pclose(popen("start /B ". $command, "r"));
//        }
//        else {
//            exec($command . " > /dev/null &");
//        }

//        $process = new Process("start /B ". $command); //working
//        $process->disableOutput();
//        $process->run();

        //pclose(popen('start /B cmd /C "'.$command.' >NUL 2>NUL"', 'r'));//working

        echo "runned";
        return 0;
    }

    public function runTool($profileId){

        $cmd=$this->buildCommand($profileId);
        //echo $cmd;die();

        $ooo=new \COM('WScript.Shell');
        $ooo->Run($cmd,0,0);
        return 0;
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