<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Shiny", mappedBy="user", cascade={"persist", "remove"})
     */
    private $shinies;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_register", type="datetime")
     * @Assert\DateTime()
     */
    private $date_register;

    public function __construct()
    {
        // Par défaut, la date d'inscription est la date d'aujourd'hui
        $this->date_register = new \Datetime();
    }

    /**
     * Add shiny
     *
     * @param \ShinyBundle\Entity\Shiny $shiny
     *
     * @return User
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
     * Set dateRegister
     *
     * @param \DateTime $dateRegister
     *
     * @return User
     */
    public function setDateRegister($dateRegister)
    {
        $this->date_register = $dateRegister;

        return $this;
    }

    /**
     * Get dateRegister
     *
     * @return \DateTime
     */
    public function getDateRegister()
    {
        return $this->date_register;
    }
}
