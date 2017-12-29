<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 19.12.2017
 * Time: 15:17
 */

namespace CasperBounty\ToolsBundle\Service;
use CasperBounty\TasksBundle\Entity\Tasks;
use Doctrine\ORM\EntityManager;

class NmapService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager, $nmapPath) {
        $this->entityManager = $entityManager;
    }

    public function startNmap($targetsObjArr,$profile){

        foreach ($targetsObjArr as $target) {
            $task = new Tasks();
            $task->setProfileid($profile)->setStatus(0)->setTargetid($target);
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->entityManager->refresh($task);
            $tasksArr[]=$task;
        }

    }

    public function runNmapTasks(){

    }

    public function parseResults(){

    }

}