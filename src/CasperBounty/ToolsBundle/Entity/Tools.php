<?php

namespace CasperBounty\ToolsBundle\Entity;

/**
 * Tools
 */
class Tools
{
    /**
     * @var integer
     */
    private $toolid;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cmdpath;

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
     * Get toolid
     *
     * @return integer
     */
    public function getToolid()
    {
        return $this->toolid;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tools
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
     * Set cmdpath
     *
     * @param string $cmdpath
     *
     * @return Tools
     */
    public function setCmdpath($cmdpath)
    {
        $this->cmdpath = $cmdpath;

        return $this;
    }

    /**
     * Get cmdpath
     *
     * @return string
     */
    public function getCmdpath()
    {
        return $this->cmdpath;
    }

    /**
     * Add templateid
     *
     * @param \CasperBounty\ToolsBundle\Entity\Templates $templateid
     *
     * @return Tools
     */
    public function addTemplateid(\CasperBounty\ToolsBundle\Entity\Templates $templateid)
    {
        $this->templateid[] = $templateid;

        return $this;
    }

    /**
     * Remove templateid
     *
     * @param \CasperBounty\ToolsBundle\Entity\Templates $templateid
     */
    public function removeTemplateid(\CasperBounty\ToolsBundle\Entity\Templates $templateid)
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

