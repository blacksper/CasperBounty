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
     * @var \CasperBounty\ProfilesBundle\Entity\Profiles
     */
    private $profileid;

    /**
     * @var \CasperBounty\ScenarioBundle\Entity\Scenario
     */
    private $scenarioid;

    /**
     * @var \CasperBounty\TargetsBundle\Entity\Targets
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
     * @param \CasperBounty\ProfilesBundle\Entity\Profiles $profileid
     *
     * @return Tasks
     */
    public function setProfileid(\CasperBounty\ProfilesBundle\Entity\Profiles $profileid = null)
    {
        $this->profileid = $profileid;

        return $this;
    }

    /**
     * Get profileid
     *
     * @return \CasperBounty\ProfilesBundle\Entity\Profiles
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * Set scenarioid
     *
     * @param \CasperBounty\ScenarioBundle\Entity\Scenario $scenarioid
     *
     * @return Tasks
     */
    public function setScenarioid(\CasperBounty\ScenarioBundle\Entity\Scenario $scenarioid = null)
    {
        $this->scenarioid = $scenarioid;

        return $this;
    }

    /**
     * Get scenarioid
     *
     * @return \CasperBounty\ScenarioBundle\Entity\Scenario
     */
    public function getScenarioid()
    {
        return $this->scenarioid;
    }

    /**
     * Set targetid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $targetid
     *
     * @return Tasks
     */
    public function setTargetid(\CasperBounty\TargetsBundle\Entity\Targets $targetid = null)
    {
        $this->targetid = $targetid;

        return $this;
    }

    /**
     * Get targetid
     *
     * @return \CasperBounty\TargetsBundle\Entity\Targets
     */
    public function getTargetid()
    {
        return $this->targetid;
    }
    /**
     * @var string
     */
    private $output;


    /**
     * Set output
     *
     * @param string $output
     *
     * @return Tasks
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get output
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }
}
