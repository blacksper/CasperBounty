<?php

namespace CasperBounty\TargetsBundle\Service;


use CasperBounty\ProjectsBundle\Entity\Projects;
use CasperBounty\TargetsBundle\Entity\Targets;
use Doctrine\DBAL\Schema\Table;
use Doctrine\ORM\EntityManager;
use function PHPSTORM_META\elementType;
use function PHPSTORM_META\type;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TargetsService
{
    private $project;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function setProjectId($project)
    {
        //dump($project);
        if (is_int($project)) {
            $repositoryp = $this->em->getRepository('CasperBountyProjectsBundle:Projects');
            $this->project = $repositoryp->find($project);
        } else if ($project instanceof Projects)
            return $this->project = $project;
        else
            return "Error type of project";
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
            ->getQuery()
            ->getArrayResult();


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

    public function checkHostsForExists($hostsArray)
    {

        //$allHostsForCheck = array();

        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');//TODO уточнить необходимость создания репозитория второй раз

        $existsTargets = $repository
            ->createQueryBuilder('t')
            ->select('t.host')
            ->where('t.host in(:allDomains)')
            ->setParameters(array('allDomains' => $hostsArray))
            ->getQuery()
            ->getArrayResult();

        $existsTargetsArr = array();
        foreach ($existsTargets as $exTarget)
            $existsTargetsArr[] = $exTarget['host'];

        $uniqHostsArray = array_unique(array_diff($hostsArray, $existsTargetsArr));
        return $uniqHostsArray;
    }

    /**
     * add hosts to db
     */

    public function addHosts($hosts, $type = null)
    {

        //$this->projectId=$projectId;
        //если не указан тип значит добавление идёт с заранее указаннымb типами хостов

        if (is_null($type)) {
            $hostsArr = explode("\r\n", $hosts);
            //print_r($hostsArr);
            if (empty($hostsArr))
                return 0;
            //echo $this->projectId;
            $hostTypeArr = $this->setHostType($hostsArr);//array ('domain'=>array(hosts),'maindomain'=>array(hosts))

            if (empty($hostTypeArr))
                return 0;
        } else if (!empty($hosts)) {
            $hostTypeArr = $hosts;
        } else {
            return "something wrong with type";
        }

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

                $this->project->addTargetid($target);//добавление цели к проекту

                $this->em->persist($this->project);
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

                $target = new Targets();

                if ($type == 'domain') {
                    $parent = $this->searchMain($host);
                    if ($parent)
                        $target->setParentid($parent);
                }

                $target->setHost($host);
                $target->setType($type);
                $this->project->addTargetid($target);//добавление цели к проекту

                $this->em->persist($this->project);
                $this->em->persist($target);

                //оно тут для получения id
                $this->em->flush();
                $this->em->refresh($target);

                $successAdded[] = $target->getTargetid();
            }
        }

        //$this->em->refresh($project);

        //$repository->clear();
        //die();
        return $successAdded;
    }

    public function addHostIpsPtr($hostsArr)
    {//=array('target'=>"ert.com",'hostnames'=>array(),'address'=>array())
        $hostsArrForCheck = array();
        $repoT = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $target = false;
        //die();

        foreach ($hostsArr as $hosts) {
            if (isset($hosts['target'])) {
                $target = $hosts['target'];
            }
            if (isset($hosts['hostnames']))
                foreach ($hosts['hostnames'] as $hostname) {
                    $hostsArrForCheck[] = $hostname;
                }

            if (isset($hosts['address']))
                foreach ($hosts['address'] as $address) {
                    $hostsArrForCheck[] = $address;
                }
        }
        $uniq = $this->checkHostsForExists($hostsArrForCheck);
        dump($hostsArr);
        dump($uniq);
        dump($target);

        if (empty($hostsArr))
            return 'hostsArr array is empty';

        if ($target) {
            $targetObj = $repoT->findBy(array('host' => $target));
            if (count($targetObj) > 1)
                return 'error, duplicate targets if db!';
            else if (count($targetObj) < 0)
                return 'error, target of the scan not found!';

            $targetObj = $targetObj[0];

        }

        $allreadyIpsInTarget = $targetObj->getIpid()->toArray();

        $targetObj->setState('up');

        foreach ($hostsArr as $host) {

            foreach ($host['address'] as $type => $addr) {
                //if ip exists, find it in db
                if (array_search($addr, $uniq) === false) {
                    $ipObject = $repoT->findBy(array('host' => $addr));
                    if (count($ipObject) > 1)
                        return 'error, duplicate targets if db!';
                    else if (count($ipObject) < 0)
                        return 'error, target of the scan not found!';
                    else
                        $ip = $ipObject[0];
                } else {
                    //creating new target object for ip
                    $ip = new Targets();
                    $ip->setHost($addr);
                    $ip->setType($type);
                    //$ip->setDateadded(new \DateTime("now"));
                }

                if (array_search($ip, $allreadyIpsInTarget) === false) {
                    //adding new ips to scan target
                    $targetObj->addIpId($ip);
                }

                //if ptr exists
                if (isset($host['hostnames']['PTR'])) {
                    $ptrStr = $host['hostnames']['PTR'];
                    //end if ptr is uniq, create new target obj for ptr
                    if (array_search($ptrStr, $uniq) === false) {
                        $ptrObj = $repoT->findBy(array('host' => $ptrStr));
                        if (count($ptrObj) > 1)
                            return 'error, duplicate targets if db!';
                        else if (count($ptrObj) < 0)
                            return 'error, target of the scan not found!';
                        else
                            $ptr = $ptrObj[0];

                    } else {
                        $ptr = new Targets();
                        $ptr->setHost($host['hostnames']['PTR']);
                        $ptr->setType(key($host['hostnames']));
                    }
                    if (array_search($ip, $ptr->getIpid()->toArray()) === false) {
                        $ptr->addIpid($ip);
                    }
                    $this->em->persist($ptr);

                }

                $this->em->persist($ip);
                $this->em->persist($targetObj);
            }


        }
        $this->em->flush();

        //dump($host);
        return 1;
    }

    public function addHost($prepareHosts)
    {
        $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        //return uniq hosts
        $uniqHosts = $this->checkHostExists($prepareHosts);

        dump($uniqHosts);
        die();
        $target = new Targets();
        $target->setHost($targetHost);
        $target->setType($type);

        //$target->set
        $this->em->persist($target);
        $this->em->flush();

        return $target;
    }

    public function addHostsFromFile($files)
    {
        $hostsStr = "";
        foreach ($files as $file) {
            //dump($file);
            $hostsStr .= file_get_contents($file->getRealPath()) . "\r\n";
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
        //$projectId = $targetInfotest->getProjectid(); //maindomain projectid
        dump($this->project);
        if ($targetInfotest->getType() == 'maindomain') {

            $query = $this->em->createQuery('
                select t from CasperBountyTargetsBundle:Targets t
                JOIN t.projectid pid
                
                WHERE t.parentid=:parentId and pid=:projectId')
                ->setMaxResults(20)//limit
                ->setParameters(array('parentId' => $targetId, 'projectId' => $this->project));


            $subTargets = $query->getResult();

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
                    'projectId' => $this->project));
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
