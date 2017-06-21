<?php

namespace CasperBounty\ProjectsBundle\Entity;

/**
 * Projects
 */
class Projects
{
    /**
     * @var integer
     */
    private $projectid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $targetid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->targetid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get projectid
     *
     * @return integer
     */
    public function getProjectid()
    {
        return $this->projectid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Projects
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add targetid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $targetid
     *
     * @return Projects
     */
    public function addTargetid(\CasperBounty\TargetsBundle\Entity\Targets $targetid)
    {
        $this->targetid[] = $targetid;

        return $this;
    }

    /**
     * Remove targetid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $targetid
     */
    public function removeTargetid(\CasperBounty\TargetsBundle\Entity\Targets $targetid)
    {
        $this->targetid->removeElement($targetid);
    }

    /**
     * Get targetid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTargetid()
    {
        return $this->targetid;
    }
}

