<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    const INTERESTING_USER = 2;
    const IGNORING_USER = 3;
    const BOT_OR_BUSINESS = 8;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $pk;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $userType;

    /**
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @ORM\Column(type="string", options={ "collation": "utf8mb4_general_ci" })
     */
    private $full_name;

    /**
     * @var Item[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="user")
     */
    private $items;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_private;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_verified;

    /**
     * @ORM\Column(type="string")
     */
    private $profile_pic_url;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $profile_pic_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $media_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $follower_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $following_count;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_business;

    /**
     * @ORM\Column(type="text", nullable=true, options={ "collation": "utf8mb4_general_ci" })
     */
    private $biography;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $external_url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $usertags_count;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isFollower = false;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * @param mixed $pk
     * @return User
     */
    public function setPk($pk)
    {
        $this->pk = $pk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param mixed $full_name
     * @return User
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * @return Item[]|Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[]|Collection $items
     * @return User
     */
    public function setItems($items): User
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisPrivate()
    {
        return $this->is_private;
    }

    /**
     * @param mixed $is_private
     * @return User
     */
    public function setIsPrivate($is_private)
    {
        $this->is_private = $is_private;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisVerified()
    {
        return $this->is_verified;
    }

    /**
     * @param mixed $is_verified
     * @return User
     */
    public function setIsVerified($is_verified)
    {
        $this->is_verified = $is_verified;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfilePicUrl()
    {
        return $this->profile_pic_url;
    }

    /**
     * @param mixed $profile_pic_url
     * @return User
     */
    public function setProfilePicUrl($profile_pic_url)
    {
        $this->profile_pic_url = $profile_pic_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfilePicId()
    {
        return $this->profile_pic_id;
    }

    /**
     * @param mixed $profile_pic_id
     * @return User
     */
    public function setProfilePicId($profile_pic_id)
    {
        $this->profile_pic_id = $profile_pic_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaCount()
    {
        return $this->media_count;
    }

    /**
     * @param mixed $media_count
     * @return User
     */
    public function setMediaCount($media_count)
    {
        $this->media_count = $media_count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFollowerCount()
    {
        return $this->follower_count;
    }

    /**
     * @param mixed $follower_count
     * @return User
     */
    public function setFollowerCount($follower_count)
    {
        $this->follower_count = $follower_count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFollowingCount()
    {
        return $this->following_count;
    }

    /**
     * @param mixed $following_count
     * @return User
     */
    public function setFollowingCount($following_count)
    {
        $this->following_count = $following_count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisBusiness()
    {
        return $this->is_business;
    }

    /**
     * @param mixed $is_business
     * @return User
     */
    public function setIsBusiness($is_business)
    {
        $this->is_business = $is_business;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param mixed $biography
     * @return User
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExternalUrl()
    {
        return $this->external_url;
    }

    /**
     * @param mixed $external_url
     * @return User
     */
    public function setExternalUrl($external_url)
    {
        $this->external_url = $external_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsertagsCount()
    {
        return $this->usertags_count;
    }

    /**
     * @param mixed $usertags_count
     * @return User
     */
    public function setUsertagsCount($usertags_count)
    {
        $this->usertags_count = $usertags_count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     * @return User
     */
    public function setUserType($userType): User
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFollower(): bool
    {
        return $this->isFollower;
    }

    /**
     * @param bool $isFollower
     * @return User
     */
    public function setIsFollower(bool $isFollower): User
    {
        $this->isFollower = $isFollower;
        return $this;
    }
}
