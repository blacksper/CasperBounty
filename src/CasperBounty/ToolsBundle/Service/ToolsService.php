<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 21.06.2017
 * Time: 13:14
 */

namespace CasperBounty\ToolsBundle\Service;
use CasperBounty\TasksBundle\Entity\Tasks;
use Doctrine\ORM\EntityManager;


class ToolsService
{
    //private $em;
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function buildCommand($profileId,Tasks $tasksId){

        $repT=$this->entityManager->getRepository('CasperBountyTasksBundle:Tasks');
        $repTar=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        $repP=$this->entityManager->getRepository('CasperBountyProfilesBundle:Profiles');
        $qb=$repP->find($profileId);
        //$qb->select('t')
        //echo $qb->getToolid()->getCmdpath();
        //echo $qb->getCmd();
        //$targetsHosts=array();

        $target=$repT->find($tasksId);
        //get targets hosts


//        foreach ($targetsArr as $targetId){
//            $targetObj=$repTar->find($targetId);
//            array_push($targetsHosts,$targetObj->getHost());
//
//        }

        $targetHost=$target->getTargetid()->getHost();
        $taskId=$tasksId->getTaskid();

        $toolPath=$qb->getToolid()->getCmdpath();
        $toolParams=$qb->getCmd();
        $toolParams=str_replace('[TARGET]', $targetHost ,$toolParams);
        $cmd="--tool=\"$toolPath\" --parameters=\"$toolParams\" --taskid=$taskId"; //
        //echo $cmd;
        //$t=$repT->find($id);
        //echo $t->getCmdpath();
       return $cmd;
        //system('');
    }

    public function getAllIps($targetsArr,$projectId){
        if(empty($targetsArr))
            return 0;

        $repository=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        //$qb=$repository->findBy(array('targetid'=>$targetsArr));
        $qb=$repository->createQueryBuilder('t');
        $targetsEntArr=$qb->select('t')->where($qb->expr()->in('t.targetid',$targetsArr))->getQuery()->getResult();

        if(empty($targetsEntArr))
            return 0;
        $tmparr=array();
        $tmparr['hostid']=array();
        $tmparr['projectId']=$projectId;
       // $tmparr['hostid']=array();

        foreach ($targetsEntArr as $target) {
            //$tmparr[$target->getTargetId()] = $target->getHost();
            $tmparr['hostid'][] = array('host'=>$target->getHost(),'id'=>$target->getTargetId());
        }

        $targetsArrJson=json_encode($tmparr,JSON_UNESCAPED_SLASHES);
        $coolstr= addslashes($targetsArrJson);
        //$coolstr=str_replace('\\\\','\\',$coolstr);

        $targets=sprintf('%s',$coolstr);
        $command="node D:\\njs\\nn\\resol.js --hosts=$targets";

        die($command);
        $ooo=new \COM('WScript.Shell');
        $ooo->Run($command,0,false);

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
        die();
        return 0;
    }

    public function runTool($profileId,$targetsArr){
        //print_r($targetsArr);
        $repoProfile=$this->entityManager->getRepository('CasperBountyProfilesBundle:Profiles');
        $repoTargets=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        $profile=$repoProfile->find($profileId);
        //$tasks=array();
        $targetsObjArr=array();
        //setting targets objects by id
        foreach ($targetsArr as $target){
            $targetsObjArr[]=$repoTargets->find($target);
        }

        $tasksArr=array();
        //Creating tasks
        foreach ($targetsObjArr as $target) {
            $task = new Tasks();
            $task->setProfileid($profile)->setStatus(1)->setTargetid($target);
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->entityManager->refresh($task);
            $tasksArr[]=$task;
        }


        $interprPath="D:\\nodejs\\node.exe";
        $execscriptPath="D:\\njs\\nn\\executtest.js";
        foreach ($tasksArr as $task) {
            $cmd = $interprPath . ' ' . $execscriptPath . ' ' . $this->buildCommand($profileId, $task) . ' ';
            echo $cmd;
            //die();

            $ooo = new \COM('WScript.Shell');
            $ooo->Run($cmd, 7, 0);
        }
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