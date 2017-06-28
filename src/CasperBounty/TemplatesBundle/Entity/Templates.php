<?php

namespace CasperBounty\TemplatesBundle\Entity;

/**
 * Templates
 */
class Templates
{
    /**
     * @var integer
     */
    private $templateid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $regexpcolumnid;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $toolid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->regexpcolumnid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->toolid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get templateid
     *
     * @return integer
     */
    public function getTemplateid()
    {
        return $this->templateid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Templates
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

    /**
     * Add regexpcolumnid
     *
     * @param \CasperBounty\RegexpBundle\Entity\Regexpcolumns $regexpcolumnid
     *
     * @return Templates
     */
    public function addRegexpcolumnid(\CasperBounty\RegexpBundle\Entity\Regexpcolumns $regexpcolumnid)
    {
        $this->regexpcolumnid[] = $regexpcolumnid;

        return $this;
    }

    /**
     * Remove regexpcolumnid
     *
     * @param \CasperBounty\RegexpBundle\Entity\Regexpcolumns $regexpcolumnid
     */
    public function removeRegexpcolumnid(\CasperBounty\RegexpBundle\Entity\Regexpcolumns $regexpcolumnid)
    {
        $this->regexpcolumnid->removeElement($regexpcolumnid);
    }

    /**
     * Get regexpcolumnid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegexpcolumnid()
    {
        return $this->regexpcolumnid;
    }

    /**
     * Add toolid
     *
     * @param \CasperBounty\ToolsBundle\Entity\Tools $toolid
     *
     * @return Templates
     */
    public function addToolid(\CasperBounty\ToolsBundle\Entity\Tools $toolid)
    {
        $this->toolid[] = $toolid;

        return $this;
    }

    /**
     * Remove toolid
     *
     * @param \CasperBounty\ToolsBundle\Entity\Tools $toolid
     */
    public function removeToolid(\CasperBounty\ToolsBundle\Entity\Tools $toolid)
    {
        $this->toolid->removeElement($toolid);
    }

    /**
     * Get toolid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getToolid()
    {
        return $this->toolid;
    }
}

