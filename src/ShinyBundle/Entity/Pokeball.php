<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pokeball
 *
 * @ORM\Table(name="pokeball")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\PokeballRepository")
 */
class Pokeball
{
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
     * @ORM\Column(name="name", type="string", length=45, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
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
     * Set name
     *
     * @param string $name
     *
     * @return pokeball
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
     * Set url
     *
     * @param string $url
     *
     * @return pokeball
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

    /**
     * Set generation
     *
     * @param \ShinyBundle\Entity\Generation $generation
     *
     * @return Pokeball
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
}
