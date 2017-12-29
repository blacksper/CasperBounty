<?php

namespace CasperBounty\ProfilesBundle\Entity;

/**
 * Profiles
 */
class Profiles
{
    /**
     * @var integer
     */
    private $profileid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cmd;

    /**
     * @var \CasperBounty\ToolsBundle\Entity\Tools
     */
    private $toolid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $taskid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taskid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get profileid
     *
     * @return integer
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Profiles
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
     * Set cmd
     *
     * @param string $cmd
     *
     * @return Profiles
     */
    public function setCmd($cmd)
    {
        $this->cmd = $cmd;

        return $this;
    }

    /**
     * Get cmd
     *
     * @return string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * Set toolid
     *
     * @param \CasperBounty\ToolsBundle\Entity\Tools $toolid
     *
     * @return Profiles
     */
    public function setToolid(\CasperBounty\ToolsBundle\Entity\Tools $toolid = null)
    {
        $this->toolid = $toolid;

        return $this;
    }

    /**
     * Get toolid
     *
     * @return \CasperBounty\ToolsBundle\Entity\Tools
     */
    public function getToolid()
    {
        return $this->toolid;
    }

    /**
     * Add taskid
     *
     * @param \CasperBounty\TasklistBundle\Entity\Tasklist $taskid
     *
     * @return Profiles
     */
    public function addTaskid(\CasperBounty\TasklistBundle\Entity\Tasklist $taskid)
    {
        $this->taskid[] = $taskid;

        return $this;
    }

    /**
     * Remove taskid
     *
     * @param \CasperBounty\TasklistBundle\Entity\Tasklist $taskid
     */
    public function removeTaskid(\CasperBounty\TasklistBundle\Entity\Tasklist $taskid)
    {
        $this->taskid->removeElement($taskid);
    }

    /**
     * Get taskid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaskid()
    {
        return $this->taskid;
    }
}
