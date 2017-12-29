<?php

namespace CasperBounty\TargetsBundle\Service;


use CasperBounty\TargetsBundle\Entity\Targets;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TargetsService
{
    private $projectId;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setProjectId($projectId){
        $this->projectId=$projectId;
    }

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
            } elseif ($this->validateDomain($host)) {
                $type = 'domain';
                preg_match("#((.*)\.)?([\w\d\-]*\.\w{2,10})#", $host, $m);
                //dump($m);
                if (empty($m[2]) && empty($m[1])) {
                    $type = "maindomain";
                }
            } else {
                $type = "";
                continue;
            }
            //echo $type;
            //TODO проверку на дубликаты входных доменов
//            echo $type."   ";
//            echo $host."<br>";
            if ($type != "")
                $hostWithType[$type][] = $host;
        }
        //die();
        //print_r($hostWithType);
        return $hostWithType;
    }


    public function validateDomain($domain)
    {

        ///Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
        if (!substr_count($domain, '.')) {
            return false;
        }

        if (stripos($domain, 'www.') === 0) {
            $domain = substr($domain, 4);
        }

        $again = 'http://' . $domain;
        //echo filter_var($again, FILTER_VALIDATE_URL);
        return filter_var($again, FILTER_VALIDATE_URL);
    }


    /** check hosts for exists in db and return non exists */

    public function checkHostExists($hostsArray)
    {
        //dump($hostsArray);
        $allHostsForCheck = array();
        foreach ($hostsArray as $type) {
            foreach ($type as $host) {
                $allHostsForCheck[] = $host;

            }
        }

        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');//TODO уточнить необходимость создания репозитория второй раз

        $existsTargets = $repository
            ->createQueryBuilder('t')
            ->select('t.host')
            ->where('t.host in(:allDomains)')
            ->setParameters(array('allDomains' => $allHostsForCheck))
            ->getQuery()->getArrayResult();


        $existsTargetsArr = array();
        foreach ($existsTargets as $exTarget)
            $existsTargetsArr[] = $exTarget['host'];

        $uniqHostsArray = array_unique(array_diff($allHostsForCheck, $existsTargetsArr));
        //dump($allHostsForCheck);
        if (empty($uniqHostsArray))
            return 0;

        //add types to targets
        $uniqHostsTypeArray = array();
        foreach ($hostsArray as $type => $domainsArr) {
            foreach ($uniqHostsArray as $host) {
                if (array_search($host, $domainsArr) !== false) {
                    $uniqHostsTypeArray[$type][] = $host;

                }

            }
        }

        //dump($uniqHostsTypeArray);
        return $uniqHostsTypeArray;
    }

    /**
     * add hosts to db
     */

    public function addHosts($targetsText)
    {
        //$this->projectId=$projectId;
        $hostsArr = explode("\r\n", $targetsText);
        //print_r($hostsArr);
        if (empty($hostsArr))
            return 0;

        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $repositoryp = $this->em->getRepository('CasperBountyProjectsBundle:Projects');
        $project = $repositoryp->find($this->projectId);
        //echo $this->projectId;
        $hostTypeArr = $this->setHostType($hostsArr);//array ('domain'=>array(hosts),'maindomain'=>array(hosts))
        if (empty($hostTypeArr))
            return 0;
        //print_r($hostTypeArr);

        $uniqueHosts = $this->checkHostExists($hostTypeArr);
        if (empty($uniqueHosts))
            return 0;
        $successAdded = array();
        //add maindomains, add to projects, delete from array maindomain
        //потому что первым делом нужно добавить maindomains чтобы остальные цеплялись к нему
        if (isset($uniqueHosts['maindomain']))
            foreach ($uniqueHosts['maindomain'] as $key => $host) {
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

                unset($uniqueHosts['maindomain'][$key]);
                $successAdded[] = $target->getTargetid();
            }


        //add other targets
        foreach ($uniqueHosts as $type => $val) {
            //dump($uniqueHosts);
            foreach ($val as $host) {
                //$parent=null;
                //$existHost = $repository->findOneBy(array('host' => $host));
                //$existHostr = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getResult();
                //$existHossq = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getSQL();
                //$existHost = $repository->createQueryBuilder('t')->where('t.targetid in (:har)')->setParameter('har',$hostsArray)->;
                $target = new Targets();

                if ($type == 'domain') {
                    $parent = $this->searchMain($host);
                    if ($parent)
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


    public function addHostsFromFile($files)
    {
        $hostsStr = "";
        foreach ($files as $file) {
            //dump($file);
            $hostsStr .= file_get_contents($file->getRealPath())."\r\n";
        }
        //dump($hostsStr);
        $newTargets = $this->addHosts($hostsStr);
        if (!empty($newTargets))
            return $newTargets;
        else
            return 0;
    }

    //get subdomains of domain
    public function getSubtargets($targetId)
    {
        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $targetInfotest = $repository->find($targetId);
        //dump($targetInfotest);
        $projectId = $targetInfotest->getProjectid(); //maindomain projectid

        if ($targetInfotest->getType() == 'maindomain') {

            $query = $this->em->createQuery('
                select t from CasperBountyTargetsBundle:Targets t
                JOIN t.projectid pid
                
                WHERE t.parentid=:parentId and pid=:projectId')
                ->setMaxResults(5)//limit
                ->setParameters(array('parentId' => $targetId, 'projectId' => $projectId));

            // echo $query->getSQL();
            $subTargets = $query->getResult();
            //dump($subTargets);
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
        //dump($m[3]);
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

    public function addIps(int $targetId, array $ipsArr, $projectId)
    {
        //dump($ipsArr);
        $rept = $this->em->getRepository('CasperBountyTargetsBundle:Targets');

        $target = $rept->find($targetId);
        //$projectId=$target->getProjectid()[0];//или править архитектуру чтоб цель использовадась только в 1 проекте или делать получение проджектид подругому
        //dump($projectId[0]);
        $addedIpsArr = array();
        //$project=$this->em->find('CasperBountyProjectsBundle:Projects',1);
        $repP = $this->em->getRepository('CasperBountyProjectsBundle:Projects');

        $project = $repP->find($projectId);
        //die();
        echo "projectid $projectId";
        foreach ($ipsArr as $ip) {
            //echo 'helo2';
            $ipExists = $this->checkIpExist($ip, $projectId);
            if ($ipExists != 0) {
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
        $exstIps = $target->getIpid();
        foreach ($addedIpsArr as $ip) {

            foreach ($exstIps as $eip) {//cheching for exist relation
                if ($ip->getTargetid() == $eip->getTargetid())
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
            ->leftJoin('t.projectid', 'targetproject')
            ->where('t.type=\'ip\'')
            ->andWhere('t.host=:ip')
            ->andWhere('targetproject.projectid=:projectid')
            ->getDQL();

        $ips = $this->em->createQuery($ipExistDql)->setParameters(array('projectid' => $projectId, 'ip' => $ip))->getResult();
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
