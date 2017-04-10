<?php

namespace SH\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SH\NewsBundle\Validator\Antiflood;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="SH\NewsBundle\Entity\NewsRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="title", message="Une News existe déjà avec ce titre.")
 */
class News
{
	/**
     * @ORM\OneToOne(targetEntity="SH\NewsBundle\Entity\Image", cascade={"persist", "remove"})
	 * @Assert\Valid()
     */
	private $image;

    /**
     * @ORM\OneToMany(targetEntity="SH\NewsBundle\Entity\Comment", mappedBy="news")
	 * @Antiflood()
     */
	private $comments;
	
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
	 * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, unique=true)
	 * @Assert\Length(min=5)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", length=255)
	 * @Assert\Length(min=2)
     */
    private $author;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
	 * @Assert\NotBlank()
     */
    private $content;
	
	/**
	 * @ORM\Column(name="published", type="boolean")
	 */
	private $published = true;
	
	/**
	 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
	 */
	private $updatedAt;


	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(length=128, unique=true)
	 */
	private $slug;

	public function __construct()
	{
	// Par défaut, la date de l'annonce est la date d'aujourd'hui
    $this->date = new \Datetime();
	$this->comments = new ArrayCollection();
  }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return News
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
     * Set title
     *
     * @param string $title
     *
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return News
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return News
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

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return News
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set image
     *
     * @param \SH\NewsBundle\Entity\Image $image
     *
     * @return News
     */
    public function setImage($image = null)
    // public function setImage(Image $image = null)
    // public function setImage(\SH\NewsBundle\Entity\Image $image = null) généré automatiquement
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \SH\NewsBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add comment
     *
     * @param \SH\NewsBundle\Entity\Comment $comment
     *
     * @return News
     */
    // public function addComment(\SH\NewsBundle\Entity\Comment $comment)
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
		
		// On lie la news au commentaire
		$comment->setNews($this);
		
        return $this;
    }

    /**
     * Remove comment
     *
     * @param \SH\NewsBundle\Entity\Comment $comment
     */
    // public function removeComment(\SH\NewsBundle\Entity\Comment $comment)
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
	
	/**
	 * @ORM\PreUpdate
	 */
	public function updateDate()
	{
		$this->setUpdatedAt(new \Datetime());
	}
	
	/**
	 * @ORM\Column(name="nb_applications", type="integer")
	 */
	private $nbComments = 0;

	public function increaseComment()
	{
		$this->nbComments++;
	}

	public function decreaseComment()
	{
		$this->nbComments--;
	}

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return News
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return News
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set nbComments
     *
     * @param integer $nbComments
     *
     * @return News
     */
    public function setNbComments($nbComments)
    {
        $this->nbComments = $nbComments;

        return $this;
    }

    /**
     * Get nbComments
     *
     * @return integer
     */
    public function getNbComments()
    {
        return $this->nbComments;
    }
}
