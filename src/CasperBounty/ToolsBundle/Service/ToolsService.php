<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 21.06.2017
 * Time: 13:14
 */

namespace CasperBounty\ToolsBundle\Service;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\TargetsBundle\Entity\Targets;
use CasperBounty\TasksBundle\Entity\Tasks;
use Doctrine\ORM\EntityManager;


class ToolsService
{
    //private $em;
    public function __construct(EntityManager $entityManager, NmapService $nmapService, $resultsDir)
    {
        $this->entityManager = $entityManager;
        $this->nmapService = $nmapService;
        $this->resultsDir=$resultsDir;
    }


    public function buildCommandv2(Profiles $profile, $targetObjects)
    {
        $commands = array();
        foreach ($targetObjects as $targetObject) {
            $commandsPrepare = $this->getCommandToTarget($targetObject, $profile);

            //its possible if IP+DOMAINS macros used
            if (is_array($commandsPrepare))
                $commands = array_merge($commands, $commandsPrepare);
            else
                $commands[] = $commandsPrepare;
        }


        dump($targetObjects);
        dump($commands);
        //die();

        return $commands;
    }

    public function getCommandToTarget(Targets $targetObject, Profiles $profile)
    {

        //$commandArray=array();
        $commandString = $profile->getCmd();

        $tool = $profile->getToolid();
        $toolPath = $tool->getCmdpath();
        $toolName = $tool->getName();


        $ips = $targetObject->getIpid();
        $host = $targetObject->getHost();
        $hostId = $targetObject->getTargetid();
        $hostType = $targetObject->getType();
        $commandStrPrepare = array();

        //$filename=$this->getFilename($host);
        if ($toolName == "nmap")
            $commandString .= " -oA [FILENAME]";

        if (strstr($commandString, '[IP+DOMAIN]')) {
            $cmdstr = str_replace('[IP+DOMAIN]', $host, $commandString);
            $commandStrPrepare[] = array('targetId' => $hostId, 'cmdStr' => $cmdstr, 'toolPath' => $toolPath);
            foreach ($ips as $ip) {
                $cmdstr = str_replace('[IP+DOMAIN]', $ip->getHost(), $commandString);
                $commandStrPrepare[] = array('targetId' => $ip->getTargetId(), 'cmdStr' => $cmdstr, 'toolPath' => $toolPath);
            }

        } else if (strstr($commandString, '[DOMAIN]') && (($hostType == 'domain') or ($hostType == 'maindomain'))) {
            $cmdstr = str_replace('[DOMAIN]', $host, $commandString);
            $commandStrPrepare[] = array('targetId' => $hostId, 'cmdStr' => $cmdstr, 'toolPath' => $toolPath);

        } else if (strstr($commandString, '[IP]') && (($hostType == 'ipv4') || ($hostType == 'ipv6'))) {
            foreach ($ips as $ip) {
                $cmdstr = str_replace('[DOMAIN]', $ip->getHost(), $commandString);
                $commandStrPrepare[] = array('targetId' => $ip->getTargetId(), 'cmdStr' => $cmdstr, 'toolPath' => $toolPath);
            }

        }

        foreach ($commandStrPrepare as &$cmdItem) {
            $fileOutput = $this->getFilename($toolName, $cmdItem['targetId']);
            $cmdItem['cmdStr'] = str_replace('[FILENAME]', $fileOutput, $cmdItem['cmdStr']);
            $cmdItem['fileOutput'] = $fileOutput;
        }


        //creating tasks


        return $commandStrPrepare;
    }


    public function getFilename($toolName, $targetId)
    {
        //$contaiter->getParameter('tool.resultssDir');
        //$resultsPath = $this->resultsDir."\\$toolName\\";
        $resultsPath = $this->resultsDir.$toolName."/";
        $filename = $resultsPath . $targetId . '_' . time();
        return $filename;
    }

    public function getAllIps($targetsArr, $projectId)
    {
        if (empty($targetsArr))
            return 0;

        $repository = $this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        //$qb=$repository->findBy(array('targetid'=>$targetsArr));
        $qb = $repository->createQueryBuilder('t');
        $targetsEntArr = $qb->select('t')->where($qb->expr()->in('t.targetid', $targetsArr))->getQuery()->getResult();

        if (empty($targetsEntArr))
            return 0;
        $tmparr = array();
        $tmparr['hostid'] = array();
        $tmparr['projectId'] = $projectId;
        // $tmparr['hostid']=array();

        foreach ($targetsEntArr as $target) {
            //$tmparr[$target->getTargetId()] = $target->getHost();
            $tmparr['hostid'][] = array('host' => $target->getHost(), 'id' => $target->getTargetId());
        }

        $targetsArrJson = json_encode($tmparr, JSON_UNESCAPED_SLASHES);
        $coolstr = addslashes($targetsArrJson);
        //$coolstr=str_replace('\\\\','\\',$coolstr);

        $targets = sprintf('%s', $coolstr);
        $command = "node D:\\njs\\nn\\resol.js --hosts=$targets";

        //die($command);
        $ooo = new \COM('WScript.Shell');
        $ooo->Run($command, 7, false);

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

    public function runTool($profileId, $targetsArr)
    {
        //print_r($targetsArr);
        $repoProfile = $this->entityManager->getRepository('CasperBountyProfilesBundle:Profiles');
        $repoTargets = $this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
        $profile = $repoProfile->find($profileId);
        $tool = $profile->getToolid()->getName();


        $targetObjects = $repoTargets->findBy(array('targetid' => $targetsArr));
        ##get coomands for each target
        $commands = $this->buildCommandv2($profile, $targetObjects);

        ##create task for each target
        if (!empty($commands)) {
            $tasksArr = array();
            foreach ($commands as &$command) {
                $target = $repoTargets->find($command['targetId']);
                $task = $this->createTask($profile, $target);

                $tasksArr[] = $task;
                $this->entityManager->persist($task);
                $this->entityManager->flush();
                //die();
                $this->entityManager->refresh($task);

                $command['taskId'] = $task->getTaskid();
                //$command['projectId']=$task->getTaskid();
            }
            $this->entityManager->flush();
        }


        dump($commands);
        //die();
        $this->runToolv2($commands);
//        die();
//
//
//        //$interprPath = "G:\\nodejs\\node.exe";
//        $interprPath = "D:\\nodejs\\node.exe";
//        //$execscriptPath = "G:\\nodeprojects\\njs\\nn\\executtest.js";
//        $execscriptPath = "D:\\njs\\nn\\executtest.js";
//        foreach ($targetsObjArr as $task) {
//            $commandpart = $this->buildCommand($profileId, $task);
//            if (!$commandpart)
//                break;
//            $cmd = $interprPath . ' ' . $execscriptPath . ' ' . $commandpart . ' ';
//            echo $cmd . "\r\n\r\n";
//            //die();
//            $ooo = new \COM('WScript.Shell');
//            $ooo->Run($cmd, 7, 0);
//            //usleep(100000);
//        }
        return 0;
    }

    public function runToolv2(array $cmdArr)
    {

//        $interprPath = "D:\\nodejs\\node.exe";
//        $execscriptPath = "G:\\nodeprojects\\njs\\nn\\executtest.js";
        $interprPath = "D:\\nodejs\\node.exe";
        $execscriptPath = "D:\\njs\\nn\\executtest.js";
        foreach ($cmdArr as $cmd) {

            $cmd =
                "--tool=\"" .
                $cmd['toolPath'] .
                "\" --parameters=\"" .
                $cmd['cmdStr'] . "\"" .
                " --taskid=" .
                $cmd['taskId'] .
                " --fileOutput=" .
                $cmd['fileOutput'] .
                ""; //
            $cmd = $interprPath . ' ' . $execscriptPath . ' ' . $cmd . ' ';
            echo $cmd . "\r\n\r\n";
            //die();
            $ooo = new \COM('WScript.Shell');
            $ooo->Run($cmd, 7, 0);
            //usleep(100000);
            //break;
        }

    }

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
    public function createTask($profile, $target, $scenarioId = null)
    {
        $task = new Tasks();
        $task->setProfileid($profile);
        $task->setTargetid($target);
        $task->setStatus(0);
        $task->setScenarioid($scenarioId);
        $task->setServiceid(null);
        //dump($task);die();
        return $task;
    }

}