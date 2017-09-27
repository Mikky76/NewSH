<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Generation
 *
 * @ORM\Table(name="generation")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\GenerationRepository")
 */
class Generation
{
    /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Pokemon", mappedBy="generation", cascade={"persist", "remove"})
     */
    private $pokemons;

   /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Pokeball", mappedBy="generation", cascade={"persist", "remove"})
     */
    private $pokeballs;

   /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Sprite", mappedBy="generation", cascade={"persist", "remove"})
     */
    private $sprites;

   /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Version", mappedBy="generation", cascade={"persist", "remove"})
     */
    private $versions;

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
     * @ORM\Column(name="generation", type="string", length=255)
     */
    private $generation;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /*************************************************************************************************
     * Generation constructor.
     *************************************************************************************************/
    public function __construct()
    {
        $this->pokemons   = new ArrayCollection();
        $this->pokeballs   = new ArrayCollection();
        $this->sprites   = new ArrayCollection();
        $this->versions   = new ArrayCollection();
    }

    /**
     * Set generation
     *
     * @param integer $generation
     *
     * @return Generation
     */
    public function setGeneration($generation)
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * Get generation
     *
     * @return int
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * Add pokemon
     *
     * @param \ShinyBundle\Entity\Pokemon $pokemon
     *
     * @return Generation
     */
    public function addPokemon(\ShinyBundle\Entity\Pokemon $pokemon)
    {
        $this->pokemons[] = $pokemon;

        return $this;
    }

    /**
     * Remove pokemon
     *
     * @param \ShinyBundle\Entity\Pokemon $pokemon
     */
    public function removePokemon(\ShinyBundle\Entity\Pokemon $pokemon)
    {
        $this->pokemons->removeElement($pokemon);
    }

    /**
     * Get pokemons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPokemons()
    {
        return $this->pokemons;
    }

    /**
     * Add pokeball
     *
     * @param \ShinyBundle\Entity\Pokeball $pokeball
     *
     * @return Generation
     */
    public function addPokeball(\ShinyBundle\Entity\Pokeball $pokeball)
    {
        $this->pokeballs[] = $pokeball;

        return $this;
    }

    /**
     * Remove pokeball
     *
     * @param \ShinyBundle\Entity\Pokeball $pokeball
     */
    public function removePokeball(\ShinyBundle\Entity\Pokeball $pokeball)
    {
        $this->pokeballs->removeElement($pokeball);
    }

    /**
     * Get pokeballs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPokeballs()
    {
        return $this->pokeballs;
    }

    /**
     * Add sprite
     *
     * @param \ShinyBundle\Entity\Sprite $sprite
     *
     * @return Generation
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

    /**
     * Add version
     *
     * @param \ShinyBundle\Entity\Version $version
     *
     * @return Generation
     */
    public function addVersion(\ShinyBundle\Entity\Version $version)
    {
        $this->versions[] = $version;

        return $this;
    }

    /**
     * Remove version
     *
     * @param \ShinyBundle\Entity\Version $version
     */
    public function removeVersion(\ShinyBundle\Entity\Version $version)
    {
        $this->versions->removeElement($version);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersions()
    {
        return $this->versions;
    }
}
