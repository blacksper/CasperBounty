<?php
//namespace namespace CasperBounty\ProjectsBundle\Entity;
namespace CasperBounty\TargetsBundle\Entity;
use Doctrine\ORM\EntityRepository;

class TargetsRepository extends EntityRepository{
    public function getTargets($projectId){

        $qb= $this->createQueryBuilder('p')->where('p.projectid > :pid')->setParameter(':pid',$projectId);
        $result=$qb->getQuery()->getResult();
        return $result;

    }

}