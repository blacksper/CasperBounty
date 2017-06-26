<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 21.06.2017
 * Time: 13:14
 */

namespace CasperBounty\ProjectsBundle\Service;
//use Doctrine\ORM\EntityManager;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bridge\Doctrine

use Doctrine\ORM\EntityManager;

class Testservice1
{
    //private $em;
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }


    public function getHappyMessage()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);
        //die('die motherfucker1');
        return $messages[$index];
    }

    public function addTargetsToProject($projectId,$targetsArr){

        //$this->em->getRepository('');
        //$em = $this->em->getDoctrine()->getManager();

        //var_dump($this);
        //$em=$this->entityManager;
        $project=$this->entityManager->getRepository('CasperBountyProjectsBundle:Projects')->find($projectId);
        $targets=$this->entityManager->getRepository('CasperBountyTargetsBundle:Targets')->findBy(array('targetid' => $targetsArr));

        foreach ($targets as $target)
            $project->addTargetid($target);

        $this->entityManager->flush();
    }
}