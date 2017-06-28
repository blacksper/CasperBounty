<?php

namespace CasperBounty\TargetsBundle\Service;


use CasperBounty\TargetsBundle\Entity\Targets;
use Doctrine\ORM\EntityManager;

class TargetsService
{
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
    
    public function checkHostExists($hostsArray){
        $successAdded=array();
        //$updatedTargets=array();
        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        foreach ($hostsArray as $host) {
            $existHost = $repository->findOneBy(array('host' => $host));

            if (!$existHost) {
                $target = new Targets();
                $target->setType('domain');
                $target->setHost($host);
                $this->em->persist($target);
                //echo $target->getTargetid();
                $this->em->flush();
                $this->em->refresh($target);
                $successAdded[] = $target->getTargetid();

                //$updatedTargets[]=$target;
            }
        }
        $repository->clear();
        //$repository->find();
//        var_dump(count($updatedTargets));
//        foreach ($updatedTargets as $target) {
//
//
//        }
        //echo $updatedTargets[0]->getTargetid();

        //die();
        var_dump($successAdded);
        return $successAdded;
    }
   
    
}
