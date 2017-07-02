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

    public function setHostType($hosts)
    {
        $hostWithType = array();
        foreach ($hosts as $host) {
            $type = "";
            //$valid = filter_var($host, FILTER_VALIDATE_IP);
            //var_dump(filter_var('2001:0db8:0000:85a3:0000:0000:ac1f:8001', FILTER_VALIDATE_IP, FILTER_FLAG_IPV4));
            //var_dump(filter_var('mandrill._domainkey.mailchimp.com', FILTER_VALIDATE_DOMAIN));
            if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $type = 'ipv4';
            } elseif(filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $type = 'ipv6';
            }elseif(filter_var($host, FILTER_VALIDATE_DOMAIN)){
                $type='domain';
            }
            //echo $type;
            $hostWithType[]=array('host'=>$host,'type'=>$type);
        }
        //print_r($hostWithType);
        return $hostWithType;
    }

    public function checkHostExists($hostsArray)
    {
        $successAdded=array();
        $repository = $this->em->getRepository('CasperBountyTargetsBundle:Targets');
        $existHost = $repository->createQueryBuilder('t')->select('t.host')->where('t.host in (:har)')->setParameter('har', $hostsArray)->getQuery();
        $rere = $existHost->getResult();
        //print_r($rere);
        $tmparr = array();
        foreach ($rere as $i) {
            $tmparr[] = $i['host'];
        }

        $nonExistsHosts = array_diff($hostsArray, $tmparr);
        //print_r($result);
        $nonExistsHosts=$this->setHostType($nonExistsHosts);
        //die();

        foreach ($nonExistsHosts as $host) {
            //$existHost = $repository->findOneBy(array('host' => $host));
            //$existHostr = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getResult();
            //$existHossq = $repository->createQueryBuilder('t')->where('t.host in (:har)')->setParameter('har',$hostsArray)->getQuery()->getSQL();
            //$existHost = $repository->createQueryBuilder('t')->where('t.targetid in (:har)')->setParameter('har',$hostsArray)->;
            $target = new Targets();
            $target->setType($host['type']);
            $target->setHost($host['host']);
            $this->em->persist($target);

            $this->em->flush();
            $this->em->refresh($target);
            $successAdded[] = $target->getTargetid();
        }

        $repository->clear();


        return $successAdded;
    }

    public function addHosts($notExtistsHostsArray)
    {

    }

}
