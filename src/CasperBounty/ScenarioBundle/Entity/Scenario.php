<?php

namespace CasperBounty\ScenarioBundle\Entity;

/**
 * Scenario
 */
class Scenario
{
    /**
     * @var integer
     */
    private $scenarioid;

    /**
     * @var string
     */
    private $scenarioname;


    /**
     * Get scenarioid
     *
     * @return integer
     */
    public function getScenarioid()
    {
        return $this->scenarioid;
    }

    /**
     * Set scenarioname
     *
     * @param string $scenarioname
     *
     * @return Scenario
     */
    public function setScenarioname($scenarioname)
    {
        $this->scenarioname = $scenarioname;

        return $this;
    }

    /**
     * Get scenarioname
     *
     * @return string
     */
    public function getScenarioname()
    {
        return $this->scenarioname;
    }
}

