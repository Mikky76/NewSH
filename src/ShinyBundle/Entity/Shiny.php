<?php

namespace ShinyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use UserBundle\Entity\User;

/**
 * shiny
 *
 * @ORM\Table(name="shiny")
 * @ORM\Entity(repositoryClass="ShinyBundle\Repository\ShinyRepository")
 */
class Shiny
{
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Location", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Methode", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $methode;

    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Pokemon", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Nature", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $nature;
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Pokeball", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokeball;
    /**
     * @ORM\ManyToOne(targetEntity="ShinyBundle\Entity\Version", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Picture", mappedBy="shiny", cascade={"persist", "remove"})
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="ShinyBundle\Entity\Video", mappedBy="shiny", cascade={"persist", "remove"})
     */
    private $videos;

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
     * @ORM\Column(name="nickname", type="string", length=45, nullable=true)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=10)
     */
    private $sexe;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=25, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="recit", type="text", nullable=true)
     */
    private $recit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /*************************************************************************************************
     * Shiny constructor.
     *************************************************************************************************/
    public function __construct()
    {
        // Par défaut, la date est la date d'aujourd'hui
        $this->date = new \Datetime();
        $this->videos   = new ArrayCollection();
        $this->pictures   = new ArrayCollection();
    }

    /*************************************************************************************************
     * Getters & Setters
     *************************************************************************************************/

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
     * Set nickname
     *
     * @param string $nickname
     *
     * @return shiny
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return shiny
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return shiny
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return shiny
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set recit
     *
     * @param string $recit
     *
     * @return shiny
     */
    public function setRecit($recit)
    {
        $this->recit = $recit;

        return $this;
    }

    /**
     * Get recit
     *
     * @return string
     */
    public function getRecit()
    {
        return $this->recit;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return shiny
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return shiny
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set methode
     *
     * @param \ShinyBundle\Entity\Methode $methode
     *
     * @return Shiny
     */
    public function setMethode(\ShinyBundle\Entity\Methode $methode)
    {
        $this->methode = $methode;

        return $this;
    }

    /**
     * Get methode
     *
     * @return \ShinyBundle\Entity\Methode
     */
    public function getMethode()
    {
        return $this->methode;
    }

    /**
     * Set pokemon
     *
     * @param \ShinyBundle\Entity\Pokemon $pokemon
     *
     * @return Shiny
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
     * Set nature
     *
     * @param \ShinyBundle\Entity\Nature $nature
     *
     * @return Shiny
     */
    public function setNature(\ShinyBundle\Entity\Nature $nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return \ShinyBundle\Entity\Nature
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set pokeball
     *
     * @param \ShinyBundle\Entity\Pokeball $pokeball
     *
     * @return Shiny
     */
    public function setPokeball(\ShinyBundle\Entity\Pokeball $pokeball)
    {
        $this->pokeball = $pokeball;

        return $this;
    }

    /**
     * Get pokeball
     *
     * @return \ShinyBundle\Entity\Pokeball
     */
    public function getPokeball()
    {
        return $this->pokeball;
    }

    /**
     * Set version
     *
     * @param \ShinyBundle\Entity\Version $version
     *
     * @return Shiny
     */
    public function setVersion(\ShinyBundle\Entity\Version $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return \ShinyBundle\Entity\Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Shiny
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add picture
     *
     * @param \ShinyBundle\Entity\Picture $picture
     *
     * @return Shiny
     */
    public function addPicture(\ShinyBundle\Entity\Picture $picture)
    {
        $this->pictures[] = $picture;

        // On lie le shiny à la photo
        $picture->setShiny($this);

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \ShinyBundle\Entity\Picture $picture
     */
    public function removePicture(\ShinyBundle\Entity\Picture $picture)
    {
        $this->pictures->removeElement($picture);

        //Relation facultative (nullable=true)
//        $picture->setShiny(null);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Add video
     *
     * @param \ShinyBundle\Entity\Video $video
     *
     * @return Shiny
     */
    public function addVideo(\ShinyBundle\Entity\Video $video)
    {
        $this->videos[] = $video;

        // On lie le shiny à la photo
        $video->setShiny($this);

        return $this;
    }

    /**
     * Remove video
     *
     * @param \ShinyBundle\Entity\Video $video
     */
    public function removeVideo(\ShinyBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Vérification du dresseur
     *
     * @return bool
     */
    public function isTrainer(User $user = null)
    {
        if($user->getUsername() === $this->getUser()){
            return true;
        }
        else{
            return false;
        }
    }
}
