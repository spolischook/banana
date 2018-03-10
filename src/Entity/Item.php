<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $pk;

    /**
     * @ORM\Column(type="integer")
     */
    private $taken_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $device_timestamp;

    /**
     * @ORM\Column(type="smallint")
     */
    private $media_type;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $client_cache_key;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $filter_type;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="item", cascade={"persist"})
     */
    private $image_versions2;

    /**
     * @ORM\OneToOne(targetEntity="Media", cascade={"persist"})
     */
    private $video_versions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_audio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $video_duration;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="carousel_parent_id", cascade={"persist"})
     */
    private $carousel_media;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="carousel_media")
     */
    private $carousel_parent_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $original_width;

    /**
     * @ORM\Column(type="integer")
     */
    private $original_height;

    /**
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     */
    private $lng;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_pk", referencedColumnName="pk")
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     */
    private $caption;

    /**
     * @ORM\Column(type="integer")
     */
    private $like_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_count;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Item
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return Item
     */
    public function setPk($pk)
    {
        $this->pk = $pk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTakenAt()
    {
        return $this->taken_at;
    }

    /**
     * @param mixed $taken_at
     * @return Item
     */
    public function setTakenAt($taken_at)
    {
        $this->taken_at = $taken_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceTimestamp()
    {
        return $this->device_timestamp;
    }

    /**
     * @param mixed $device_timestamp
     * @return Item
     */
    public function setDeviceTimestamp($device_timestamp)
    {
        $this->device_timestamp = $device_timestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaType()
    {
        return $this->media_type;
    }

    /**
     * @param mixed $media_type
     * @return Item
     */
    public function setMediaType($media_type)
    {
        $this->media_type = $media_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return Item
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientCacheKey()
    {
        return $this->client_cache_key;
    }

    /**
     * @param mixed $client_cache_key
     * @return Item
     */
    public function setClientCacheKey($client_cache_key)
    {
        $this->client_cache_key = $client_cache_key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterType()
    {
        return $this->filter_type;
    }

    /**
     * @param mixed $filter_type
     * @return Item
     */
    public function setFilterType($filter_type)
    {
        $this->filter_type = $filter_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageVersions2()
    {
        return $this->image_versions2;
    }

    /**
     * @param mixed $image_versions2
     * @return Item
     */
    public function setImageVersions2($image_versions2)
    {
        $this->image_versions2 = $image_versions2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideoVersions()
    {
        return $this->video_versions;
    }

    /**
     * @param mixed $video_versions
     * @return Item
     */
    public function setVideoVersions($video_versions)
    {
        $this->video_versions = $video_versions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasAudio()
    {
        return $this->has_audio;
    }

    /**
     * @param mixed $has_audio
     * @return Item
     */
    public function setHasAudio($has_audio)
    {
        $this->has_audio = $has_audio;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVideoDuration()
    {
        return $this->video_duration;
    }

    /**
     * @param mixed $video_duration
     * @return Item
     */
    public function setVideoDuration($video_duration)
    {
        $this->video_duration = $video_duration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarouselMedia()
    {
        return $this->carousel_media;
    }

    /**
     * @param mixed $carousel_media
     * @return Item
     */
    public function setCarouselMedia($carousel_media)
    {
        $this->carousel_media = $carousel_media;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarouselParentId()
    {
        return $this->carousel_parent_id;
    }

    /**
     * @param mixed $carousel_parent_id
     * @return Item
     */
    public function setCarouselParentId($carousel_parent_id)
    {
        $this->carousel_parent_id = $carousel_parent_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalWidth()
    {
        return $this->original_width;
    }

    /**
     * @param mixed $original_width
     * @return Item
     */
    public function setOriginalWidth($original_width)
    {
        $this->original_width = $original_width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginalHeight()
    {
        return $this->original_height;
    }

    /**
     * @param mixed $original_height
     * @return Item
     */
    public function setOriginalHeight($original_height)
    {
        $this->original_height = $original_height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     * @return Item
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     * @return Item
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Item
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     * @return Item
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLikeCount()
    {
        return $this->like_count;
    }

    /**
     * @param mixed $like_count
     * @return Item
     */
    public function setLikeCount($like_count)
    {
        $this->like_count = $like_count;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommentCount()
    {
        return $this->comment_count;
    }

    /**
     * @param mixed $comment_count
     * @return Item
     */
    public function setCommentCount($comment_count)
    {
        $this->comment_count = $comment_count;
        return $this;
    }
}
