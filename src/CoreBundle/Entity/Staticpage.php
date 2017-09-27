<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Staticpage
 *
 * @ORM\Table(name="staticpage")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\StaticpageRepository")
 */
class Staticpage
{
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
     * @ORM\Column(name="name_page", type="string", length=255)
     */
    private $namePage;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


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
     * Set namePage
     *
     * @param string $namePage
     *
     * @return Static
     */
    public function setNamePage($namePage)
    {
        $this->namePage = $namePage;

        return $this;
    }

    /**
     * Get namePage
     *
     * @return string
     */
    public function getNamePage()
    {
        return $this->namePage;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Static
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

