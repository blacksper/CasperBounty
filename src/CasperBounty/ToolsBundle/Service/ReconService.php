<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 21.06.2017
 * Time: 13:14
 */

namespace CasperBounty\ToolsBundle\Service;
use CasperBounty\ProfilesBundle\Entity\Profiles;
use CasperBounty\TasksBundle\Entity\Tasks;
use Doctrine\ORM\EntityManager;


class ReconService
{
    private $aquatonePath;
    public function __construct(EntityManager $entityManager,$aquatonePath) {
        $this->entityManager = $entityManager;
        $this->aquatonePath=$aquatonePath;
    }

    public function runAquatone($target){

        if(is_int($target)){
            $targetId=$target;
            $targetsRepo=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets');
            $targetObj=$targetsRepo->find($targetId);
        }else{
            return 0;
        }



        echo $this->aquatonePath;
    }

}