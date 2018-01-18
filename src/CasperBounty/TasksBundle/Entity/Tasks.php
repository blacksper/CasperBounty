<?php

namespace CasperBounty\TasksBundle\Entity;
//use Symfony\Component\Validator\Constraints\DateTime;

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

    function __construct()
    {
        $this->timestart = new \DateTime();
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
    /**
     * @var \DateTime
     */
    private $timestart = 'CURRENT_TIMESTAMP';


    /**
     * @var \DateTime
     */
    private $timeend;


    /**
     * Set timestart
     *
     * @param \DateTime $timestart
     *
     * @return Tasks
     */
    public function setTimestart($timestart)
    {
        $this->timestart = $timestart;

        return $this;
    }

    /**
     * Get timestart
     *
     * @return \DateTime
     */
    public function getTimestart()
    {
        return $this->timestart;
    }

    /**
     * Set timeend
     *
     * @param \DateTime $timeend
     *
     * @return Tasks
     */
    public function setTimeend($timeend)
    {
        $this->timeend = $timeend;

        return $this;
    }

    /**
     * Get timeend
     *
     * @return \DateTime
     */
    public function getTimeend()
    {
        return $this->timeend;
    }
    /**
     * @var \CasperBounty\ServicesBundle\Entity\Services
     */
    private $serviceid;


    /**
     * Set serviceid
     *
     * @param \CasperBounty\ServicesBundle\Entity\Services $serviceid
     *
     * @return Tasks
     */
    public function setServiceid(\CasperBounty\ServicesBundle\Entity\Services $serviceid = null)
    {
        $this->serviceid = $serviceid;

        return $this;
    }

    /**
     * Get serviceid
     *
     * @return \CasperBounty\ServicesBundle\Entity\Services
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }
    /**
     * @var \CasperBounty\ServicesBundle\Entity\Services
     */
    //private $services;


    /**
     * Set services
     *
     * @param \CasperBounty\ServicesBundle\Entity\Services $services
     *
     * @return Tasks
     */
//    public function setServices(\CasperBounty\ServicesBundle\Entity\Services $services = null)
//    {
//        $this->services = $services;
//
//        return $this;
//    }

    /**
     * Get services
     *
     * @return \CasperBounty\ServicesBundle\Entity\Services
     */
//    public function getServices()
//    {
//        return $this->services;
//    }
    /**
     * @var integer
     */
    //private $tasks;


    /**
     * Get tasks
     *
     * @return integer
     */
//    public function getTasks()
//    {
//        return $this->tasks;
//    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $results;


    /**
     * Add result
     *
     * @param \CasperBounty\ResultsBundle\Entity\Results $result
     *
     * @return Tasks
     */
    public function addResult(\CasperBounty\ResultsBundle\Entity\Results $result)
    {
        $this->results[] = $result;

        return $this;
    }

    /**
     * Remove result
     *
     * @param \CasperBounty\ResultsBundle\Entity\Results $result
     */
    public function removeResult(\CasperBounty\ResultsBundle\Entity\Results $result)
    {
        $this->results->removeElement($result);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set results
     *
     * @param \CasperBounty\ResultsBundle\Entity\Results $results
     *
     * @return Tasks
     */
    public function setResults(\CasperBounty\ResultsBundle\Entity\Results $results = null)
    {
        $this->results = $results;

        return $this;
    }
}
