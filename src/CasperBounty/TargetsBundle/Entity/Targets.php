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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groupid;

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
}

