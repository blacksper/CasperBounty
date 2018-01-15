<?php

namespace CasperBounty\ServicesBundle\Entity;

/**
 * Services
 */
class Services
{
    /**
     * @var integer
     */
    private $serviceid;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var boolean
     */
    private $state;

    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $version;

    /**
     * @var \CasperBounty\TargetsBundle\Entity\Targets
     */
    private $targetid;


    /**
     * Get serviceid
     *
     * @return integer
     */
    public function getServiceid()
    {
        return $this->serviceid;
    }

    /**
     * Set port
     *
     * @param integer $port
     *
     * @return Services
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return Services
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set service
     *
     * @param string $service
     *
     * @return Services
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return Services
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set targetid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $targetid
     *
     * @return Services
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
     * Set serviceid
     *
     * @param integer $serviceid
     *
     * @return Services
     */
    public function setServiceid($serviceid)
    {
        $this->serviceid = $serviceid;

        return $this;
    }
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
     * Add taskid
     *
     * @param \CasperBounty\ServicesBundle\Entity\Services $taskid
     *
     * @return Services
     */
    public function addTaskid(\CasperBounty\ServicesBundle\Entity\Services $taskid)
    {
        $this->taskid[] = $taskid;

        return $this;
    }

    /**
     * Remove taskid
     *
     * @param \CasperBounty\ServicesBundle\Entity\Services $taskid
     */
    public function removeTaskid(\CasperBounty\ServicesBundle\Entity\Services $taskid)
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tasks;


    /**
     * Add task
     *
     * @param \CasperBounty\TasksBundle\Entity\Tasks $task
     *
     * @return Services
     */
    public function addTask(\CasperBounty\TasksBundle\Entity\Tasks $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \CasperBounty\TasksBundle\Entity\Tasks $task
     */
    public function removeTask(\CasperBounty\TasksBundle\Entity\Tasks $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
    /**
     * @var integer
     */
    private $services;


    /**
     * Get services
     *
     * @return integer
     */
    public function getServices()
    {
        return $this->services;
    }
}
