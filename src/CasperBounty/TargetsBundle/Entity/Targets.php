<?php

namespace CasperBounty\TargetsBundle\Entity;

/**
 * Targets
 */
class Targets
{
    /**
     * @var integer
     */
    private $targetid;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $state;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groupid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $domainid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $serviceid;


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ipid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $projectid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groupid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ipid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projectid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->domainid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviceid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dateadded = new \DateTime('now');
    }

    /**
     * Get targetid
     *
     * @return integer
     */
    public function getTargetid()
    {
        return $this->targetid;
    }

    /**
     * Set host
     *
     * @param string $host
     *
     * @return Targets
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Targets
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return Targets
     */
    public function setState(string $state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Add groupid
     *
     * @param \CasperBounty\GroupsBundle\Entity\Groups $groupid
     *
     * @return Targets
     */
    public function addGroupid(\CasperBounty\GroupsBundle\Entity\Groups $groupid)
    {
        $this->groupid[] = $groupid;

        return $this;
    }

    /**
     * Remove groupid
     *
     * @param \CasperBounty\GroupsBundle\Entity\Groups $groupid
     */
    public function removeGroupid(\CasperBounty\GroupsBundle\Entity\Groups $groupid)
    {
        $this->groupid->removeElement($groupid);
    }

    /**
     * Get groupid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupid()
    {
        return $this->groupid;
    }

    /**
     * Add ipid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $ipid
     *
     * @return Targets
     */
    public function addIpid(\CasperBounty\TargetsBundle\Entity\Targets $ipid)
    {
        $this->ipid[] = $ipid;

        return $this;
    }


    /**
     * @param \Doctrine\Common\Collections\Collection $serviceid
     */
    public function addServiceid(\CasperBounty\ServicesBundle\Entity\Services $serviceid)
    {
        $this->serviceid[] = $serviceid;

        return $this;
    }

    /**
     * Remove ipid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $ipid
     */
    public function removeIpid(\CasperBounty\TargetsBundle\Entity\Targets $ipid)
    {
        $this->ipid->removeElement($ipid);
    }

    /**
     * Get ipid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIpid()
    {
        return $this->ipid;
    }

    /**
     * Add projectid
     *
     * @param \CasperBounty\ProjectsBundle\Entity\Projects $projectid
     *
     * @return Targets
     */
    public function addProjectid(\CasperBounty\ProjectsBundle\Entity\Projects $projectid)
    {
        $this->projectid[] = $projectid;

        return $this;
    }

    /**
     * Remove projectid
     *
     * @param \CasperBounty\ProjectsBundle\Entity\Projects $projectid
     */
    public function removeProjectid(\CasperBounty\ProjectsBundle\Entity\Projects $projectid)
    {
        $this->projectid->removeElement($projectid);
    }

    /**
     * Get projectid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectid()
    {
        return $this->projectid;
    }

    /**
     * @var \CasperBounty\TargetsBundle\Entity\Targets
     */
    private $parentid;


    /**
     * Set parentid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $parentid
     *
     * @return Targets
     */
    public function setParentid(\CasperBounty\TargetsBundle\Entity\Targets $parentid = null)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return \CasperBounty\TargetsBundle\Entity\Targets
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * @var \DateTime
     */
    private $dateadded = 'CURRENT_TIMESTAMP';

    /**
     * Set dateadded
     *
     * @param \DateTime $dateadded
     *
     * @return Targets
     */
    public function setDateadded(\DateTime $dateadded)
    {
        $this->dateadded = $dateadded;

        return $this;
    }

    /**
     * Get dateadded
     *
     * @return \DateTime
     */
    public function getDateadded()
    {
        return $this->dateadded;
    }

    /**
     * Add domainid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $domainid
     *
     * @return Targets
     */
    public function addDomainid(\CasperBounty\TargetsBundle\Entity\Targets $domainid)
    {
        $this->domainid[] = $domainid;

        return $this;
    }

    /**
     * Remove domainid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $domainid
     */
    public function removeDomainid(\CasperBounty\TargetsBundle\Entity\Targets $domainid)
    {
        $this->domainid->removeElement($domainid);
    }

    /**
     * Get domainid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomainid()
    {
        return $this->domainid;
    }

    /**
     * Get serviceid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
}
