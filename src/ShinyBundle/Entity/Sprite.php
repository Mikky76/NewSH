<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sprite
 *
 * @ORM\Table(name="sprite")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\SpriteRepository")
 */
class Sprite
{
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Pokemon", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Generation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

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
     * @ORM\Column(name="url", type="text", unique=false)
     */
    private $url;


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
     * Set pokemon
     *
     * @param \ShinyBundle\Entity\Pokemon $pokemon
     *
     * @return Sprite
     */
    public function setPokemon(\ShinyBundle\Entity\Pokemon $pokemon)
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    /**
     * Get pokemon
     *
     * @return \ShinyBundle\Entity\Pokemon
     */
    public function getPokemon()
    {
        return $this->pokemon;
    }

    /**
     * Set generation
     *
     * @param \ShinyBundle\Entity\Generation $generation
     *
     * @return Sprite
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
     * Set url
     *
     * @param string $url
     *
     * @return Sprite
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
