<?php

namespace CasperBounty\ResultsBundle\Entity;

/**
 * Results
 */
class Results
{
    /**
     * @var integer
     */
    private $resultid;

    /**
     * @var string
     */
    private $result;
    
    /**
     * @var \CasperBounty\TargetsBundle\Entity\Targets
     */
    private $targetid;

    /**
     * @var \CasperBounty\ProfilesBundle\Entity\Profiles
     */
    private $profileid;


    /**
     * Get resultid
     *
     * @return integer
     */
    public function getResultid()
    {
        return $this->resultid;
    }

    /**
     * Set result
     *
     * @param string $result
     *
     * @return Results
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set targetid
     *
     * @param \CasperBounty\TargetsBundle\Entity\Targets $targetid
     *
     * @return Results
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
     * Set profileid
     *
     * @param \CasperBounty\ProfilesBundle\Entity\Profiles $profileid
     *
     * @return Results
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
}

