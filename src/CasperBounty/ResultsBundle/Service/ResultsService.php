<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 13.11.2017
 * Time: 14:30
 */

namespace CasperBounty\ResultsBundle\Service;


use CasperBounty\ResultsBundle\Entity\Results;
use CasperBounty\TargetsBundle\Service\TargetsService;
use CasperBounty\TasksBundle\Entity\Tasks;
use Doctrine\ORM\EntityManager;
use function PHPSTORM_META\type;

class ResultsService
{
    private $taskId;

    public function __construct(EntityManager $entityManager, TargetsService $targetsService)
    {
        $this->targetsService = $targetsService;
        $this->em = $entityManager;
    }

    public function setTaskId($taskId)
    {
        return $this->taskId = $taskId;
    }

    //add new ips,hosts after ping
    public function resultsHandle($resultData, $fileOutput)
    {
        $tasksRepo = $this->em->getRepository('CasperBountyTasksBundle:Tasks');
        $resultsRepo = $this->em->getRepository('CasperBountyResultsBundle:Results');
        $task = $tasksRepo->find($this->taskId);
        $profile = $task->getProfileid();
        $toolName = $profile->getToolId()->getName();//меньше доверия тому что данные существуют
        //$project=$task->getTargetid();
        $project = $task->getTargetid()->getProjectid()[0];//поправить иначе при пересечении целей в разных проектах, всё пойдёт по пизде


        dump($project);
        if ($toolName == "nmap" && $fileOutput)
            $resultData = file_get_contents(trim($fileOutput));

        $results = new Results();

        $results->setProfileid($profile);
        $results->setResult(htmlentities(urldecode($resultData)));
        $results->setTaskid($task);
        $this->em->persist($results);

        dump($fileOutput);
        dump($resultData);

        $xml = simplexml_load_string($resultData);

        switch ($profile->getName()) {
            case 'ping':
                $this->targetsService->setProjectId($project);
                $this->handlePingResult($xml);
                break;
            default:
                dump($xml);
                break;
        }


        //die();
        //$addedHost = $this->targetsService->addHosts($prepareHosts, 1);
        //$project->addTargetid($addedHost);
        //$this->em->flush();
       //dump($addedHost);
        die();
    }

    public function handlePingResult($xml)
    {
        $prepareHosts = array();
        dump($xml);
        $i = 0;
        foreach ($xml->host as $host) {
            foreach ($host->hostnames->hostname as $hostname) {
                $hname = (string)$hostname['name'];
                $htype = (string)$hostname['type'];
                if ($htype == "user") {
                    $prepareHosts[$i]['target'] = $hname;
                    continue;
                }
                $prepareHosts[$i]['hostnames'][$htype] = $hname;
            }

            foreach ($host->address as $address) {
                $addr = (string)$address['addr'];
                $addrType = (string)$address['addrtype'];
                $prepareHosts[$i]['address'][$addrType] = $addr;
            }
            $i++;
        }
        dump($prepareHosts);

        if (!empty($prepareHosts)) {
            $this->targetsService->addHostIpsPtr($prepareHosts);
        } else {
            return 0;
//            $target = $task->getTargetid();
//            $target->setState('not_resolve');
//            $this->em->flush();
//            dump($target);
        }
        return 1;
    }

    public function parseNmap(Tasks $task, $result)
    {
        $cmd = $task->getProfileid()->getCmd();
        if (strstr('[FILENAME]', $cmd)) {
            $filename = $result['filename'];
            echo $filename;
        } else {

        }


    }

}