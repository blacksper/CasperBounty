<?php

namespace CasperBounty\RegexpBundle\Entity;

/**
 * Regexp
 */
class Regexp
{
    /**
     * @var integer
     */
    private $regexpid;

    /**
     * @var string
     */
    private $regexp;

    /**
     * @var string
     */
    private $name;


    /**
     * Get regexpid
     *
     * @return integer
     */
    public function getRegexpid()
    {
        return $this->regexpid;
    }

    /**
     * Set regexp
     *
     * @param string $regexp
     *
     * @return Regexp
     */
    public function setRegexp($regexp)
    {
        $this->regexp = $regexp;

        return $this;
    }

    /**
     * Get regexp
     *
     * @return string
     */
    public function getRegexp()
    {
        return $this->regexp;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Regexp
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

