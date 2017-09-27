<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Version
 *
 * @ORM\Table(name="version")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\VersionRepository")
 */
class Version
{
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Generation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    /**
     * @ORM\ManyToMany(targetEntity="ShinyBundle\Entity\Location", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $locations;

    /**
     * @ORM\ManyToMany(targetEntity="ShinyBundle\Entity\Methode", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $methodes;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, unique=true)
     */
    private $name;

    /*************************************************************************************************
     * Constructor.
     *************************************************************************************************/
    public function __construct()
    {
        $this->locations   = new ArrayCollection();
        $this->methodes   = new ArrayCollection();
    }

    /*************************************************************************************************
     * Getters & Setters
     *************************************************************************************************/




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Version
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
     * Set generation
     *
     * @param \ShinyBundle\Entity\Generation $generation
     *
     * @return Version
     */
    public function setGeneration(\ShinyBundle\Entity\Generation $generation)
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * Get generation
     *
     * @return \ShinyBundle\Entity\Generation
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * Add location
     *
     * @param \ShinyBundle\Entity\Location $location
     *
     * @return Version
     */
    public function addLocation(\ShinyBundle\Entity\Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \ShinyBundle\Entity\Location $location
     */
    public function removeLocation(\ShinyBundle\Entity\Location $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Add methode
     *
     * @param \ShinyBundle\Entity\Methode $methode
     *
     * @return Version
     */
    public function addMethode(\ShinyBundle\Entity\Methode $methode)
    {
        $this->methodes[] = $methode;

        return $this;
    }

    /**
     * Remove methode
     *
     * @param \ShinyBundle\Entity\Methode $methode
     */
    public function removeMethode(\ShinyBundle\Entity\Methode $methode)
    {
        $this->methodes->removeElement($methode);
    }

    /**
     * Get methodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMethodes()
    {
        return $this->methodes;
    }
}
