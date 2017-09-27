<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Shiny", inversedBy="pictures", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="shiny_id", referencedColumnName="id", nullable=false)
     */
    private $shiny;

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
     * @ORM\Column(name="url", type="string", length=255, unique=true)
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
     * Set url
     *
     * @param string $url
     *
     * @return picture
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
     * Set shiny
     *
     * @param \ShinyBundle\Entity\Shiny $shiny
     *
     * @return Picture
     */
    public function setShiny(\ShinyBundle\Entity\Shiny $shiny)
    {
        $this->shiny = $shiny;

        return $this;
    }

    /**
     * Get shiny
     *
     * @return \ShinyBundle\Entity\Shiny
     */
    public function getShiny()
    {
        return $this->shiny;
    }
}
