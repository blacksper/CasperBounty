<?php

namespace CasperBounty\TasksBundle\Entity;

/**
 * Tasks
 */
class Tasks
{
    /**
     * @var integer
     */
    private $taskid;

    /**
     * @var boolean
     */
    private $status;

    /**
     * @var \CasperBounty\TasksBundle\Entity\Profiles
     */
    private $profileid;

    /**
     * @var \CasperBounty\TasksBundle\Entity\Scenario
     */
    private $scenarioid;

    /**
     * @var \CasperBounty\TasksBundle\Entity\Targets
     */
    private $targetid;


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
     * Set status
     *
     * @param boolean $status
     *
     * @return Tasks
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set profileid
     *
     * @param \CasperBounty\TasksBundle\Entity\Profiles $profileid
     *
     * @return Tasks
     */
    public function setProfileid(\CasperBounty\TasksBundle\Entity\Profiles $profileid = null)
    {
        $this->profileid = $profileid;

        return $this;
    }

    /**
     * Get profileid
     *
     * @return \CasperBounty\TasksBundle\Entity\Profiles
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * Set scenarioid
     *
     * @param \CasperBounty\TasksBundle\Entity\Scenario $scenarioid
     *
     * @return Tasks
     */
    public function setScenarioid(\CasperBounty\TasksBundle\Entity\Scenario $scenarioid = null)
    {
        $this->scenarioid = $scenarioid;

        return $this;
    }

    /**
     * Get scenarioid
     *
     * @return \CasperBounty\TasksBundle\Entity\Scenario
     */
    public function getScenarioid()
    {
        return $this->scenarioid;
    }

    /**
     * Set targetid
     *
     * @param \CasperBounty\TasksBundle\Entity\Targets $targetid
     *
     * @return Tasks
     */
    public function setTargetid(\CasperBounty\TasksBundle\Entity\Targets $targetid = null)
    {
        $this->targetid = $targetid;

        return $this;
    }

    /**
     * Get targetid
     *
     * @return \CasperBounty\TasksBundle\Entity\Targets
     */
    public function getTargetid()
    {
        return $this->targetid;
    }
}

