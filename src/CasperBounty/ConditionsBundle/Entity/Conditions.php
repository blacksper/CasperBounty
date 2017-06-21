<?php

namespace CasperBounty\ConditionsBundle\Entity;

/**
 * Conditions
 */
class Conditions
{
    /**
     * @var integer
     */
    private $conditid;

    /**
     * @var string
     */
    private $condition;


    /**
     * Get conditid
     *
     * @return integer
     */
    public function getConditid()
    {
        return $this->conditid;
    }

    /**
     * Set condition
     *
     * @param string $condition
     *
     * @return Conditions
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }
}

