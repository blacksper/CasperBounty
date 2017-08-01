<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 21.06.2017
 * Time: 13:14
 */

namespace CasperBounty\ProfilesBundle\Service;
use CasperBounty\ProfilesBundle\Entity\Profiles;
use Doctrine\ORM\EntityManager;

class ProfileService
{
    //private $em;
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }


    public function addProfile($toolId){
        $em=$this->entityManager;
        //$repProfiles=$em->getRepository('CasperBountyProfilesBundle:Profiles');
        $repTools=$em->getRepository('CasperBountyToolsBundle:Tools');

        $profile=new Profiles();
        $tool=$repTools->find($toolId);
        if(!$tool)
            return 0;
        $profile->setToolid($tool);
        $this->entityManager->persist($profile);
        $this->entityManager->flush();

        return 1;
    }
}