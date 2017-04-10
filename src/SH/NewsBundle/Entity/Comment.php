<?php
// src/SH/NewsBundle/Entity/Comment.php

namespace SH\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="SH\NewsBundle\Entity\CommentRepository")
 */
class Comment
{
  /**
   * @ORM\ManyToOne(targetEntity="SH\NewsBundle\Entity\News", inversedBy="comments")
   * @ORM\JoinColumn(nullable=false)
   */
  private $news;

  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(name="author", type="string", length=255)
   */
  private $author;

  /**
   * @ORM\Column(name="content", type="text")
   */
  private $content;

  /**
   * @ORM\Column(name="date", type="datetime")
   */
  private $date;
  
  /**
   * @ORM\Column(name="ip", type="string", length=255)
   */
  private $ip;

  public function __construct()
  {
    $this->date = new \Datetime();
  }

  public function getId()
  {
    return $this->id;
  }

  public function setAuthor($author)
  {
    $this->author = $author;

    return $this;
  }

  public function getAuthor()
  {
    return $this->author;
  }

  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function setDate($date)
  {
    $this->date = $date;

    return $this;
  }

  public function getDate()
  {
    return $this->date;
  }

    /**
     * Set news
     *
     * @param \SH\NewsBundle\Entity\News $news
     *
     * @return Comment
     */
    // public function setNews(\SH\NewsBundle\Entity\News $news)
    public function setNews(News $news)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return \SH\NewsBundle\Entity\News
     */
    public function getNews()
    {
        return $this->news;
    }
	/**
	 * @ORM\PrePersist
	 */
	public function increase()
	{
		$this->getNews()->increaseComment();
	}

	/**
	 * @ORM\PreRemove
	 */
	public function decrease()
	{
		$this->getNews()->decreaseComment();
	}

  /**
   * @return mixed
   */
  public function getIp()
  {
    return $this->ip;
  }

  /**
   * @param mixed $ip
   */
  public function setIp($ip)
  {
    $this->ip = $ip;
  }
}
