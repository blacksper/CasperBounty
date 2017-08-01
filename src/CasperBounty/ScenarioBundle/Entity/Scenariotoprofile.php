<?php

namespace CasperBounty\ScenarioBundle\Entity;

/**
 * Scenariotoprofile
 */
class Scenariotoprofile
{
    /**
     * @var integer
     */
    private $scenprofid;

    /**
     * @var \CasperBounty\ScenarioBundle\Entity\Profiles
     */
    private $profileid;

    /**
     * @var \CasperBounty\ScenarioBundle\Entity\Scenario
     */
    private $scenarioid;


    /**
     * Get scenprofid
     *
     * @return integer
     */
    public function getScenprofid()
    {
        return $this->scenprofid;
    }

    /**
     * Set profileid
     *
     * @param \CasperBounty\ScenarioBundle\Entity\Profiles $profileid
     *
     * @return Scenariotoprofile
     */
    public function setProfileid(\CasperBounty\ScenarioBundle\Entity\Profiles $profileid = null)
    {
        $this->profileid = $profileid;

        return $this;
    }

    /**
     * Get profileid
     *
     * @return \CasperBounty\ScenarioBundle\Entity\Profiles
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
     * @return Scenariotoprofile
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
}

