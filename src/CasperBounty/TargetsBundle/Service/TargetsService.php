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
                dump($m);
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

    /** check hosts for exists in db and return non exists */

    public function checkHostExists($hostsArray)
    {

        $maindomains=array();
        //set maindomains
        foreach ($hostsArray as $host){
            preg_match("#((.*)\.)?([\w\d\-]*\.\w{2,10})#", trim($host), $m);
            //echo "<br>".$host." shit";

            if(empty($m))
                continue;

            if (!array_search($m[3],$maindomains)&&!array_search($m[3],$hostsArray)) {
                $maindomains[] = $m[3];
            }
        }

        $hostsArray=array_unique(array_merge($hostsArray,$maindomains));
        //dump($hostsArray);die();

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
        //echo $this->projectId;

        $uniqueHosts = $this->checkHostExists($hostsArr);
        if (empty($uniqueHosts))
            return 0;
        $hostTypeArr = $this->setHostType($uniqueHosts);//array ('domain'=>array(hosts),'maindomain'=>array(hosts))
        //print_r($hostTypeArr);
        //die();
        $successAdded = array();
        //add maindomains, add to projects, delete from array maindomain
        if (isset($hostTypeArr['maindomain']))
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
                JOIN  t.ipid ipid
                WHERE t.parentid=:parentId and pid=:projectId and ipid.host like \'185.73.193.%\'')
                ->setParameters(array('parentId' => $targetId, 'projectId' => $projectId));

            // echo $query->getSQL();
            $subTargets = $query->getResult();
            dump($subTargets);
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

    public function addIps(int $targetId, array $ipsArr,$projectId)
    {
        //dump($ipsArr);
        $rept = $this->em->getRepository('CasperBountyTargetsBundle:Targets');

        $target = $rept->find($targetId);
        //$projectId=$target->getProjectid()[0];//или править архитектуру чтоб цель использовадась только в 1 проекте или делать получение проджектид подругому
        //dump($projectId[0]);
        $addedIpsArr = array();
        //$project=$this->em->find('CasperBountyProjectsBundle:Projects',1);
        $repP=$this->em->getRepository('CasperBountyProjectsBundle:Projects');

        $project=$repP->find($projectId);
        //die();
        echo "projectid $projectId";
        foreach ($ipsArr as $ip) {
            //echo 'helo2';
            $ipExists = $this->checkIpExist($ip,$projectId);
            if ($ipExists!=0) {
                $addedIpsArr[] = $ipExists[0];
                continue;
            }
            $ipObj = new Targets();
            $ipObj->setHost($ip);
            $ipObj->setType('ip');

            //$ipObj->addProjectid($project);//не работает

            $this->em->persist($ipObj);
            $this->em->flush();
            $this->em->refresh($ipObj);
            $project->addTargetid($ipObj);
            $addedIpsArr[] = $ipObj;
        }
        
        //dump($addedIpsArr);
        $exstIps=$target->getIpid();
        foreach ($addedIpsArr as $ip) {

            foreach ($exstIps as $eip){//cheching for exist relation
                if($ip->getTargetid()==$eip->getTargetid())
                    continue 2;
            }

            //$exst=$target->;
            //$exst=$target->getIpid($ip)[0];
            //$tid=$exst->getTargetId();
            //dump($exst);
            //if($exst) {
           //     echo "exists<br>";
            //    continue;
            //}
            $target->addIpid($ip);
        }

        $this->em->persist($target);
        $this->em->flush();

    }

    public function getTargetIps($targetId)
    {
        $rept = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $target = $rept->find(79);
        $ips = $target->getIpid();
        //echo count($ips[0]);
        echo $ips[0]->getHost();
        die();
    }

    public function checkIpExist($ip, $projectId)
    {
        $repT = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $ipExistDql = $repT->createQueryBuilder('t')
            ->leftJoin('t.projectid','targetproject')
            ->where('t.type=\'ip\'')
            ->andWhere('t.host=:ip')
            ->andWhere('targetproject.projectid=:projectid')
            ->getDQL();

        $ips=$this->em->createQuery($ipExistDql)->setParameters(array('projectid'=>$projectId,'ip'=> $ip))->getResult();
        //$ipExist = $repT->findBy(array('host' => $ip, 'type' => 'ip'));
        //dump($ipExistDql);
        //dump($ips);
        //die();
        if (!empty($ips) && (count($ips) == 1))
            return $ips;
        else
            return 0;
    }
}
