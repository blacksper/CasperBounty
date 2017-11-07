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

    public function buildCommand($profileId,$targetId,$targetsArr){

        $repT=$this->entityManager->getRepository('CasperBountyTasksBundle:Tasks');
        $repTar=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        $repP=$this->entityManager->getRepository('CasperBountyProfilesBundle:Profiles');
        $qb=$repP->find($profileId);

        $targetsObjArr=array();//array for target objects
        //$target=$repT->find($tasksId);
        //$targetHost=$target->getTargetid()->getHost();
        //$taskId=$tasksId->getTaskid();
        foreach ($targetsArr as $targettmp){
            $targetsObjArr[]=$repTar->find($targettmp);
        }
        $argumentsArray=array();

        $toolPath=$qb->getToolid()->getCmdpath();
        $toolParams=$qb->getCmd();
        $targetObj=$repTar->find($targetId);
        if(strripos($toolParams, '[DOMAIN]')) {
            if($targetObj->getType()=='domain') {
                $argumentsArray[] = str_replace('[DOMAIN]', $targetObj->getHost(), $toolParams);
            }
        }
        elseif(strripos($toolParams, '[IP]')) {
            if($targetObj->getType()=='ip') {
                $argumentsArray[] = str_replace('[IP]', $targetObj->getHost(), $toolParams);
            }
        }
        elseif(strripos($toolParams, '[BOTH]'))//return array with commands
        {
            if($targetObj->getTargetid()->getType()=='domain'||$targetObj->getTargetid()->getType()=='maindomain')
            {
                $ips=$targetObj->getTargetid()->getIpid();
                $targets=$repTar->findBy(array('targetid'=>$ips)); //give ips obj by ip ids
                foreach ($targets as $target){
                    //$targetsObjArr[]=$target->getHost();
                    $targetsObjArr[]=$target;
                    $argumentsArray[] = str_replace('[IP]', $targetObj->getHost(), $toolParams);
                }
                //$toolParams = str_replace('[BOTH]', $targetHost, $toolParams);

            }

        }
        else
            return 0;//wrong parameter
        //setting profile by profileid
        $profile=$repP->find($profileId);

        //creating tasks by targets object
        foreach ($targetsObjArr as $target) {
            $task = new Tasks();
            $task->setProfileid($profile)->setStatus(1)->setTargetid($target);
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->entityManager->refresh($task);
            $tasksArr[]=$task;
        }


        //$toolParams=str_replace('[TARGET]', $targetHost ,$toolParams);
        foreach ($argumentsArray as $param)
        $cmd="--tool=\"$toolPath\" --parameters=\"$param\" --taskid=$taskId"; //

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

        //die($command);
        $ooo=new \COM('WScript.Shell');
        $ooo->Run($command,7,false);

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
        //die();
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
        $tmparr=array();
        if(strripos($profile->getCmd(), '[BOTH]')){
            foreach ($targetsObjArr as $item) {
                $ips=$item->getIpId();
                $tmparr[]=$repoTargets->findBy(array('targetid'=>$ips));
            }
            $targetsObjArr=array_merge($targetsObjArr,$tmparr);
        }

        $tasksArr=array();
        //Creating tasks
//        foreach ($targetsObjArr as $target) {
//            $task = new Tasks();
//            $task->setProfileid($profile)->setStatus(1)->setTargetid($target);
//            $this->entityManager->persist($task);
//            $this->entityManager->flush();
//            $this->entityManager->refresh($task);
//            $tasksArr[]=$task;
//        }


        $interprPath="D:\\nodejs\\node.exe";
        $execscriptPath="D:\\njs\\nn\\executtest.js";
        foreach ($targetsObjArr as $task) {
            $commandpart=$this->buildCommand($profileId, $task);
            if(!$commandpart)
                break;
            $cmd = $interprPath . ' ' . $execscriptPath . ' ' . $commandpart . ' ';
            echo $cmd."\r\n\r\n";
            //die();
            $ooo = new \COM('WScript.Shell');
            $ooo->Run($cmd, 7, 0);
            //usleep(100000);
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
public function getIpByDomain(){

}

}