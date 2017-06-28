<?php

namespace CasperBounty\ConditionsBundle\Entity;

/**
 * Conditiontores
 */
class Conditiontores
{
    /**
     * @var \CasperBounty\ConditionsBundle\Entity\Conditions
     */
    private $conditionid;

    /**
     * @var \CasperBounty\ProfilesBundle\Entity\Profiles
     */
    private $profileid;

    /**
     * @var \CasperBounty\ResultsBundle\Entity\Results
     */
    private $resultid;


    /**
     * Set conditionid
     *
     * @param \CasperBounty\ConditionsBundle\Entity\Conditions $conditionid
     *
     * @return Conditiontores
     */
    public function setConditionid(\CasperBounty\ConditionsBundle\Entity\Conditions $conditionid = null)
    {
        $this->conditionid = $conditionid;

        return $this;
    }

    /**
     * Get conditionid
     *
     * @return \CasperBounty\ConditionsBundle\Entity\Conditions
     */
    public function getConditionid()
    {
        return $this->conditionid;
    }

    /**
     * Set profileid
     *
     * @param \CasperBounty\ProfilesBundle\Entity\Profiles $profileid
     *
     * @return Conditiontores
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
     * Set resultid
     *
     * @param \CasperBounty\ResultsBundle\Entity\Results $resultid
     *
     * @return Conditiontores
     */
    public function setResultid(\CasperBounty\ResultsBundle\Entity\Results $resultid = null)
    {
        $this->resultid = $resultid;

        return $this;
    }

    /**
     * Get resultid
     *
     * @return \CasperBounty\ResultsBundle\Entity\Results
     */
    public function getResultid()
    {
        return $this->resultid;
    }
}

