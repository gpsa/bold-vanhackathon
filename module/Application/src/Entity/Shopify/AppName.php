<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 23/06/18
 * Time: 14:49
 */

namespace Application\Entity\Shopify;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class AppReviews
 * @package Application\Entity\Shopify
 * @ORM\Entity(repositoryClass="AppNameRepository")
 * @ORM\Table(name="shopify_app_name")
 */
class AppName implements \JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\Column(type="string",name="app_slug")
     */
    protected $appSlug;

    /** @ORM\Column(type="string") * */
    protected $name;

    /**
     * @return mixed
     */
    public function getAppSlug()
    {
        return $this->appSlug;
    }

    /**
     * @param mixed $appSlug
     * @return AppName
     */
    public function setAppSlug($appSlug)
    {
        $this->appSlug = $appSlug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return AppName
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}