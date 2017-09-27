<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

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
}
