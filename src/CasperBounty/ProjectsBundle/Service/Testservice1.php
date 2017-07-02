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

        var_dump($targetsArr);
        //die();
        $em=$this->entityManager;
        $repositoryProjects=$em->getRepository('CasperBountyProjectsBundle:Projects');
        $repositoryTargets=$em->getRepository('CasperBountyTargetsBundle:Targets');

        $project=$repositoryProjects->find($projectId);
        $targets=$repositoryTargets->findBy(array('targetid' => $targetsArr));
        echo count($targets);
        //die();
        foreach ($targets as $target)
            $project->addTargetid($target);

        $em->flush();
    }
}