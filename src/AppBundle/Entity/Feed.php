<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Feed
 *
 * @ORM\Table(name="feed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeedRepository")
 */
class Feed
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
     * @ORM\Column(name="title", type="string", length=40, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=40, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", unique=true, length=255)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_url", type="string", length=255)
     */
    private $feed_url;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=10, nullable=true)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="copyright", type="string", length=255, nullable=true)
     */
    private $copyright;

    /**
     * @var int
     *
     * @ORM\Column(name="ttl", type="integer")
     */
    private $ttl;

    /**
     * @var mixed
     *
     * @ORM\Column(name="last_updated", type="datetime")
     */
    private $lastUpdated;


    /**
     * One feed has Many items.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Item", mappedBy="feed", fetch="EXTRA_LAZY")
     */
    private $items;


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
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Feed
     */
    public function setSlug(string $slug): Feed
    {
        $this->slug = $slug;
        return $this;
    }



    /**
     * Set name
     *
     * @param string $title
     *
     * @return Feed
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $link
     *
     * @return Feed
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getFeedUrl(): string
    {
        return $this->feed_url;
    }

    /**
     * @param string $feed_url
     * @return Feed
     */
    public function setFeedUrl(string $feed_url): Feed
    {
        $this->feed_url = $feed_url;
        return $this;
    }



    /**
     * Set description
     *
     * @param string $description
     *
     * @return Feed
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Feed
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set copyright
     *
     * @param string $copyright
     *
     * @return Feed
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Get copyright
     *
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Set ttl
     *
     * @param integer $ttl
     *
     * @return Feed
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get ttl
     *
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @return mixed
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * @param mixed $lastUpdated
     * @return Feed
     */
    public function setLastUpdated($lastUpdated = null)
    {
        $this->lastUpdated = $lastUpdated ?: new \DateTime();
        return $this;
    }




    /**
     * Set items
     *
     * @param string $items
     *
     * @return Feed
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return string
     */
    public function getItems()
    {
        return $this->items;
    }
}
