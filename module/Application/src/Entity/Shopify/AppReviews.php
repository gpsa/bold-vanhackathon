<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 10:44
 */

namespace Application\Entity\Shopify;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Class AppReviews
 * @package Application\Entity\Shopify
 * @ORM\Entity(repositoryClass="AppReviewsRepository")
 * @ORM\Table(name="shopify_app_reviews")
 * @ORM\HasLifecycleCallbacks
 */
class AppReviews implements \JsonSerializable
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** @ORM\Column(type="string",name="shopify_domain") * */
    protected $shopifyDomain;

    /**
     * AppName
     * @var AppName
     * @ORM\ManyToOne(targetEntity="AppName")
     * @ORM\JoinColumn(name="app_slug", referencedColumnName="app_slug", unique=false)
     */
    protected $appSlug;

    /** @ORM\Column(type="integer",name="star_rating") * */
    protected $starRating;

    /** @ORM\Column(type="integer", nullable = true,name="previous_star_rating") * */
    protected $previousStarRating;

    /**
     * @var DateTime $created
     *
     * @ORM\Column(type="datetime",name="created_at")
     */
    protected $createdAt;

    /**
     * @var DateTime $updated
     *
     * @ORM\Column(type="datetime", nullable = true, name="updated_at")
     */
    protected $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return AppReviews
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShopifyDomain()
    {
        return $this->shopifyDomain;
    }

    /**
     * @param mixed $shopifyDomain
     * @return AppReviews
     */
    public function setShopifyDomain($shopifyDomain)
    {
        $this->shopifyDomain = $shopifyDomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppSlug()
    {
        return $this->appSlug;
    }

    /**
     * @param mixed $appSlug
     * @return AppReviews
     */
    public function setAppSlug($appSlug)
    {
        $this->appSlug = $appSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStarRating()
    {
        return $this->starRating;
    }

    /**
     * @param mixed $star_rating
     * @return AppReviews
     */
    public function setStarRating($star_rating)
    {
        $this->starRating = $star_rating;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreviousStarRating()
    {
        return $this->previousStarRating;
    }

    /**
     * @param mixed $previousStarRating
     * @return AppReviews
     */
    public function setPreviousStarRating($previousStarRating)
    {
        $this->previousStarRating = $previousStarRating;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return AppReviews
     */
    public function setCreatedAt(DateTime $createdAt): AppReviews
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return AppReviews
     */
    public function setUpdatedAt(DateTime $updatedAt): AppReviews
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if (empty($this->created_at)) {
            $this->setCreatedAt(new \DateTime("now"));
        }
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate(PreUpdateEventArgs $event)
    {

        $this->setUpdatedAt(new \DateTime("now"));

        if ($event->hasChangedField('starRating') && !is_numeric($this->getPreviousStarRating())) {
            $this->setPreviousStarRating($event->getOldValue('starRating'));
        }
    }

    public function jsonSerialize()
    {
        $return = get_object_vars($this);
        $return['appSlug'] =[
            'appSlug' => $return['appSlug']->getAppSlug(),
            'name' => $return['appSlug']->getName()
        ];

        return $return;
    }


}