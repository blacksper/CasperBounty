<?php

namespace CasperBounty\RegexpBundle\Entity;

/**
 * Regexpresult
 */
class Regexpresult
{
    /**
     * @var integer
     */
    private $regexpresultid;

    /**
     * @var string
     */
    private $result;

    /**
     * @var \CasperBounty\RegexpBundle\Entity\Regexp
     */
    private $regexpid;

    /**
     * @var \CasperBounty\RegexpBundle\Entity\Results
     */
    private $resultid;


    /**
     * Get regexpresultid
     *
     * @return integer
     */
    public function getRegexpresultid()
    {
        return $this->regexpresultid;
    }

    /**
     * Set result
     *
     * @param string $result
     *
     * @return Regexpresult
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
     * Set regexpid
     *
     * @param \CasperBounty\RegexpBundle\Entity\Regexp $regexpid
     *
     * @return Regexpresult
     */
    public function setRegexpid(\CasperBounty\RegexpBundle\Entity\Regexp $regexpid = null)
    {
        $this->regexpid = $regexpid;

        return $this;
    }

    /**
     * Get regexpid
     *
     * @return \CasperBounty\RegexpBundle\Entity\Regexp
     */
    public function getRegexpid()
    {
        return $this->regexpid;
    }

    /**
     * Set resultid
     *
     * @param \CasperBounty\RegexpBundle\Entity\Results $resultid
     *
     * @return Regexpresult
     */
    public function setResultid(\CasperBounty\RegexpBundle\Entity\Results $resultid = null)
    {
        $this->resultid = $resultid;

        return $this;
    }

    /**
     * Get resultid
     *
     * @return \CasperBounty\RegexpBundle\Entity\Results
     */
    public function getResultid()
    {
        return $this->resultid;
    }
}

