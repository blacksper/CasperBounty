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
     * @var \CasperBounty\ConditionsBundle\Entity\Profiles
     */
    private $profileid;

    /**
     * @var \CasperBounty\ConditionsBundle\Entity\Results
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
     * @param \CasperBounty\ConditionsBundle\Entity\Profiles $profileid
     *
     * @return Conditiontores
     */
    public function setProfileid(\CasperBounty\ConditionsBundle\Entity\Profiles $profileid = null)
    {
        $this->profileid = $profileid;

        return $this;
    }

    /**
     * Get profileid
     *
     * @return \CasperBounty\ConditionsBundle\Entity\Profiles
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * Set resultid
     *
     * @param \CasperBounty\ConditionsBundle\Entity\Results $resultid
     *
     * @return Conditiontores
     */
    public function setResultid(\CasperBounty\ConditionsBundle\Entity\Results $resultid = null)
    {
        $this->resultid = $resultid;

        return $this;
    }

    /**
     * Get resultid
     *
     * @return \CasperBounty\ConditionsBundle\Entity\Results
     */
    public function getResultid()
    {
        return $this->resultid;
    }
}

