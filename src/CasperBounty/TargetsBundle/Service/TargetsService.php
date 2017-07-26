<?php

namespace CasperBounty\TargetsBundle\Service;


use CasperBounty\TargetsBundle\Entity\Targets;
use Doctrine\ORM\EntityManager;


class TargetsService
{
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    var $projectId;

    /** set hosts type */
    public function setHostType($hosts)
    {
        $hostWithType = array();
        foreach ($hosts as $host) {
            $type = "";
            if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $type = 'ipv4';
            } elseif (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $type = 'ipv6';
            } elseif (filter_var($host, FILTER_VALIDATE_DOMAIN)) {
                $type = 'domain';

                preg_match("#((.*)\.)?([\w\d\-]*\.\w{2,10})#", $host, $m);
                //print_r($m);
                if (empty($m[2]) && empty($m[1])) {
                    $type = "maindomain";
                }
                //echo $type;
                //print_r($m);
                //die();

            } else {
                continue;
            }
            //echo $type;
            //TODO проверку на дубликаты входных доменов
            $hostWithType[$type][] = $host;
        }
        //die();
        //print_r($hostWithType);
        return $hostWithType;
    }

    /** check hosts for exists in db */

    public function checkHostExists($hostsArray)
    {

        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');//TODO уточнить необходимость создания репозитория второй раз
        $existHost = $repository->createQueryBuilder('t')->select('t.host')->where('t.host in (:har)')->setParameter('har', $hostsArray)->getQuery();
        $rere = $existHost->getResult();
        //print_r($rere);
        $tmparr = array();
        foreach ($rere as $i) {
            $tmparr[] = $i['host'];
        }

        $nonExistsHosts = array_diff($hostsArray, $tmparr);
        return $nonExistsHosts;
    }

    /**
     * add hosts to db
     */

    public function addHosts($hostsArr)
    {
        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $repositoryp = $this->em->getRepository('CasperBountyProjectsBundle:Projects');
        $project = $repositoryp->find($this->projectId);
        echo $this->projectId;

        $uniqueHosts = $this->checkHostExists($hostsArr);
        if (empty($uniqueHosts))
            return 0;

        $hostTypeArr = $this->setHostType($uniqueHosts);//array ('domain'=>array(hosts),'maindomain'=>array(hosts))
        print_r($hostTypeArr);
        $successAdded = array();
        //add maindomains, add to projects, delete from array maindomain
        foreach ($hostTypeArr['maindomain'] as $key => $host) {
            $target = new Targets();

            $target->setType('maindomain');
            $target->setHost($host);

            $project->addTargetid($target);//добавление цели к проекту

            $this->em->persist($project);
            $this->em->persist($target);

            //оно тут для получения id
            $this->em->flush();
            $this->em->refresh($target);
            //$this->em->refresh($project); //возможно если убрать это то будут проблемы с дубликатами

            unset($hostTypeArr['maindomain'][$key]);
            $successAdded[] = $target->getTargetid();
        }


        //add other targets
        foreach ($hostTypeArr as $type => $val) {
            foreach ($val as $host) {
                //$parent=null;
                //$existHost = $repository->findOneBy(array('host' => $host));
                //$existHostr = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getResult();
                //$existHossq = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getSQL();
                //$existHost = $repository->createQueryBuilder('t')->where('t.targetid in (:har)')->setParameter('har',$hostsArray)->;
                $target = new Targets();

                if ($type == 'domain') {
                    $parent = $this->searchMain($host);
                    $target->setParentid($parent);
                }

                $target->setHost($host);
                $target->setType($type);
                $project->addTargetid($target);//добавление цели к проекту

                $this->em->persist($project);
                $this->em->persist($target);

                //оно тут для получения id
                $this->em->flush();
                $this->em->refresh($target);

                $successAdded[] = $target->getTargetid();
            }
        }

        //$this->em->refresh($project);

        $repository->clear();
        //die();
        return $successAdded;
    }

    public function isMainDomain($hosts)
    {
        //$repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        foreach ($hosts as $host) {
            preg_match("#(.*)\.([\w\d\-]*\.\w{2,10})#", $host, $m);
            print_r($m);
        }
        die();

    }

    public function getSubtargets($targetId)
    {
        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $targetInfotest = $repository->find($targetId);
        $projectId = $targetInfotest->getProjectid(); //maindomain projectid

        if ($targetInfotest->getType() == 'maindomain') {

//            $query = $this->em->createQuery('
//                select t from CasperBountyTargetsBundle:Targets t
//                JOIN t.projectid pid
//                WHERE t.host
//                like :maindomainHost and t.type!=\'maindomain\' and pid=:projectId')
//                ->setParameters(array('maindomainHost' => '%.' . $targetInfotest->getHost(),
//                    'projectId' => $projectId));

            $query = $this->em->createQuery('
                select t from CasperBountyTargetsBundle:Targets t
                JOIN t.projectid pid
                WHERE t.parentid=:parentId and pid=:projectId')
                ->setParameters(array('parentId' => $targetId, 'projectId' => $projectId));

            // echo $query->getSQL();
            $subTargets = $query->getResult();
            //echo count($subTargets);
        } else {
            $subTargets = 0;
        }

        return $subTargets;
    }

    //return id of parent domain
    public function searchMain($host)
    {
        $parent = null;
        preg_match('#((.*)\.)?([\w\d\-]*\.\w{2,10})#', $host, $m);
        if (isset($m[3])) { //m[3] contain hostname
            $mainDomain = $m[3];

            $query = $this->em->createQuery('
                select t from CasperBountyTargetsBundle:Targets t
                JOIN t.projectid pid
                WHERE t.host=:maindomainHost and pid=:projectId')
                ->setParameters(array('maindomainHost' => $mainDomain,
                    'projectId' => $this->projectId));
            $result = $query->getResult();
            if (count($result) == 1) {//if result is finded and count is 1
                $parent = $result[0];
            }
        }
        return $parent;
    }

}
