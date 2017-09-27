<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pokemon
 *
 * @ORM\Table(name="pokemon")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\PokemonRepository")
 */
class Pokemon
{
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Generation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Shiny", mappedBy="pokemon", cascade={"persist", "remove"})
     */
    private $shinies;

    /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Sprite", mappedBy="pokemon", cascade={"persist", "remove"})
     */
    private $sprites;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="pokedex", type="integer")
     */
    private $pokedex;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * Get id
     *
     * @return int
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
     * @return pokemon
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
     * Set pokedex
     *
     * @param integer $pokedex
     *
     * @return Pokemon
     */
    public function setPokedex($pokedex)
    {
        $this->pokedex = $pokedex;

        return $this;
    }

    /**
     * Get pokedex
     *
     * @return integer
     */
    public function getPokedex()
    {
        return $this->pokedex;
    }

    /**
     * Set generation
     *
     * @param \ShinyBundle\Entity\Generation $generation
     *
     * @return Pokemon
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
     * Constructor
     */
    public function __construct()
    {
        $this->shinies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add shiny
     *
     * @param \ShinyBundle\Entity\Shiny $shiny
     *
     * @return Pokemon
     */
    public function addShiny(\ShinyBundle\Entity\Shiny $shiny)
    {
        $this->shinies[] = $shiny;

        return $this;
    }

    /**
     * Remove shiny
     *
     * @param \ShinyBundle\Entity\Shiny $shiny
     */
    public function removeShiny(\ShinyBundle\Entity\Shiny $shiny)
    {
        $this->shinies->removeElement($shiny);
    }

    /**
     * Get shinies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShinies()
    {
        return $this->shinies;
    }

    /**
     * Add sprite
     *
     * @param \ShinyBundle\Entity\Sprite $sprite
     *
     * @return Pokemon
     */
    public function addSprite(\ShinyBundle\Entity\Sprite $sprite)
    {
        $this->sprites[] = $sprite;

        return $this;
    }

    /**
     * Remove sprite
     *
     * @param \ShinyBundle\Entity\Sprite $sprite
     */
    public function removeSprite(\ShinyBundle\Entity\Sprite $sprite)
    {
        $this->sprites->removeElement($sprite);
    }

    /**
     * Get sprites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSprites()
    {
        return $this->sprites;
    }
}
