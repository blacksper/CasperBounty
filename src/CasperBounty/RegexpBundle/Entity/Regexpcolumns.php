<?php

namespace CasperBounty\RegexpBundle\Entity;

/**
 * Regexpcolumns
 */
class Regexpcolumns
{
    /**
     * @var integer
     */
    private $regexpcolumnid;

    /**
     * @var string
     */
    private $columnname;

    /**
     * @var \CasperBounty\RegexpBundle\Entity\Regexpresult
     */
    private $regexpresultid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $templateid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->templateid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set regexpcolumnid
     *
     * @param integer $regexpcolumnid
     *
     * @return Regexpcolumns
     */
    public function setRegexpcolumnid($regexpcolumnid)
    {
        $this->regexpcolumnid = $regexpcolumnid;

        return $this;
    }

    /**
     * Get regexpcolumnid
     *
     * @return integer
     */
    public function getRegexpcolumnid()
    {
        return $this->regexpcolumnid;
    }

    /**
     * Set columnname
     *
     * @param string $columnname
     *
     * @return Regexpcolumns
     */
    public function setColumnname($columnname)
    {
        $this->columnname = $columnname;

        return $this;
    }

    /**
     * Get columnname
     *
     * @return string
     */
    public function getColumnname()
    {
        return $this->columnname;
    }

    /**
     * Set regexpresultid
     *
     * @param \CasperBounty\RegexpBundle\Entity\Regexpresult $regexpresultid
     *
     * @return Regexpcolumns
     */
    public function setRegexpresultid(\CasperBounty\RegexpBundle\Entity\Regexpresult $regexpresultid = null)
    {
        $this->regexpresultid = $regexpresultid;

        return $this;
    }

    /**
     * Get regexpresultid
     *
     * @return \CasperBounty\RegexpBundle\Entity\Regexpresult
     */
    public function getRegexpresultid()
    {
        return $this->regexpresultid;
    }

    /**
     * Add templateid
     *
     * @param \CasperBounty\TemplatesBundle\Entity\Templates $templateid
     *
     * @return Regexpcolumns
     */
    public function addTemplateid(\CasperBounty\TemplatesBundle\Entity\Templates $templateid)
    {
        $this->templateid[] = $templateid;

        return $this;
    }

    /**
     * Remove templateid
     *
     * @param \CasperBounty\TemplatesBundle\Entity\Templates $templateid
     */
    public function removeTemplateid(\CasperBounty\TemplatesBundle\Entity\Templates $templateid)
    {
        $this->templateid->removeElement($templateid);
    }

    /**
     * Get templateid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTemplateid()
    {
        return $this->templateid;
    }
}

