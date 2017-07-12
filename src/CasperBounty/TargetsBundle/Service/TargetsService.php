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

    /** set hosts type */
    public function setHostType($hosts)
    {
        $hostWithType = array();
        foreach ($hosts as $host) {
            $type = "";
            if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $type = 'ipv4';
            } elseif(filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $type = 'ipv6';
            }elseif(filter_var($host, FILTER_VALIDATE_DOMAIN)){
                $type='domain';
                preg_match("#(.*)\.([\w\d\-]*\.\w{2,10})#",$host,$m);
                if(empty($m))
                    $type="maindomain";
            }else{
                continue;
            }
            //echo $type;
            //TODO проверку на дубликаты входных доменов
            $hostWithType[$type][]=$host;
        }
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
        $uniqueHosts=$this->checkHostExists($hostsArr);
        if(empty($uniqueHosts))
            return 0;

        $hostTypeArr=$this->setHostType($uniqueHosts);

        $successAdded=array();
        foreach ($hostTypeArr as $type=>$val) {
            foreach ($val as $host) {
                //$existHost = $repository->findOneBy(array('host' => $host));
                //$existHostr = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getResult();
                //$existHossq = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getSQL();
                //$existHost = $repository->createQueryBuilder('t')->where('t.targetid in (:har)')->setParameter('har',$hostsArray)->;
                $target = new Targets();
                $target->setType($type);
                $target->setHost($host);
                $this->em->persist($target);

                $this->em->flush();
                $this->em->refresh($target);
                $successAdded[] = $target->getTargetid();
            }
        }

        $repository->clear();
        return $successAdded;
    }

    public function isMainDomain($hosts){
        //$repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        foreach ($hosts as $host) {
            preg_match("#(.*)\.([\w\d\-]*\.\w{2,10})#",$host,$m);
            print_r($m);
        }
        die();

    }

}
