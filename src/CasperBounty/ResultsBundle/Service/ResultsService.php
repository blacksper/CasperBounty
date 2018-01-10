<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 13.11.2017
 * Time: 14:30
 */

namespace CasperBounty\ResultsBundle\Service;


use CasperBounty\ResultsBundle\Entity\Results;
use CasperBounty\ServicesBundle\Entity\Services;
use CasperBounty\TargetsBundle\Service\TargetsService;
use CasperBounty\TasksBundle\Entity\Tasks;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use function PHPSTORM_META\type;
use Symfony\Component\Config\Definition\Exception\Exception;

class ResultsService
{
    private $taskId;
    private $target;

    public function __construct(EntityManager $entityManager, TargetsService $targetsService)
    {
        $this->targetsService = $targetsService;
        $this->em = $entityManager;
    }

    public function setTaskId(int $taskId)
    {
        return $this->taskId = $this->em->getRepository('CasperBountyTasksBundle:Tasks')->find($taskId);
    }

    public function setTarget(){
        $this->taskId->getTargetid();
    }

    //add new ips,hosts after ping
    public function resultsHandle($resultData, $fileOutput)
    {
        $tasksRepo = $this->em->getRepository('CasperBountyTasksBundle:Tasks');
        //$resultsRepo = $this->em->getRepository('CasperBountyResultsBundle:Results');
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
            case 'ping1':
                $this->targetsService->setProjectId($project);
                $res = $this->handlePingResult($xml);
                dump($res);
                if (!$res) {
                    $target = $task->getTargetid();
                    $target->setState('not_resolve');
                    $this->em->flush();
                }

                break;
            default:
                $this->target=$task->getTargetid();
                $this->handleNmapResults($xml);
                dump($xml);
                break;
        }



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
        }
        return 1;
    }

    public function handleNmapResults($xml){
        dump($xml);
        $hosts=$xml->host;

        $openPorts=array();
        foreach ($hosts as $host) {
            foreach ($host->ports->port as $port){
                //dump($port['portid']);
                $portnum=(string)$port['portid'];
                $state=(string)$port->state['state'];
                $serviceName=(string)$port->service['name'];
                $openPorts[]=array('port'=>$port,'state'=>$state);
                dump($port);
                $service=new Services();
                $service->setTargetid($this->target);
                $service->setPort($portnum);
                $service->setState($state);
                $service->setService($serviceName);

                $this->em->persist($service);
            }
        }

        try
        {
            $this->em->flush();
        }
        catch(UniqueConstraintViolationException $e)
        {
            //$this->em
        }

        dump($openPorts);



        //dump($this->target->addServiceid());

        die();
        return 0;
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