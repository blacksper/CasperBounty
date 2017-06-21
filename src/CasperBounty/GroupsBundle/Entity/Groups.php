<?php

namespace CasperBounty\GroupsBundle\Entity;

/**
 * Groups
 */
class Groups
{
    /**
     * @var integer
     */
    private $groupid;

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
     * Get groupid
     *
     * @return integer
     */
    public function getGroupid()
    {
        return $this->groupid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Groups
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
     * @param \CasperBounty\GroupsBundle\Entity\Targets $targetid
     *
     * @return Groups
     */
    public function addTargetid(\CasperBounty\GroupsBundle\Entity\Targets $targetid)
    {
        $this->targetid[] = $targetid;

        return $this;
    }

    /**
     * Remove targetid
     *
     * @param \CasperBounty\GroupsBundle\Entity\Targets $targetid
     */
    public function removeTargetid(\CasperBounty\GroupsBundle\Entity\Targets $targetid)
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

