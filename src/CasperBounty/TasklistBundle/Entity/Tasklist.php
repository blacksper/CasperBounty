<?php

namespace CasperBounty\TasklistBundle\Entity;

/**
 * Tasklist
 */
class Tasklist
{
    /**
     * @var integer
     */
    private $taskid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $profileid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profileid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get taskid
     *
     * @return integer
     */
    public function getTaskid()
    {
        return $this->taskid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tasklist
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
     * Add profileid
     *
     * @param \CasperBounty\ProfilesBundle\Entity\Profiles $profileid
     *
     * @return Tasklist
     */
    public function addProfileid(\CasperBounty\ProfilesBundle\Entity\Profiles $profileid)
    {
        $this->profileid[] = $profileid;

        return $this;
    }

    /**
     * Remove profileid
     *
     * @param \CasperBounty\ProfilesBundle\Entity\Profiles $profileid
     */
    public function removeProfileid(\CasperBounty\ProfilesBundle\Entity\Profiles $profileid)
    {
        $this->profileid->removeElement($profileid);
    }

    /**
     * Get profileid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfileid()
    {
        return $this->profileid;
    }
}
