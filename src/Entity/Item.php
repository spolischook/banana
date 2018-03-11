<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    const PHOTO = 1;
    const VIDEO = 2;
    const ALBUM = 8;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $takenAt;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $deviceTimestamp;

    /**
     * @ORM\Column(type="smallint")
     */
    private $mediaType;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $clientCacheKey;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $filterType;

    /**
     * @JMS\Type("ArrayCollection<App\Entity\Media>")
     * @JMS\Accessor(getter="getImageVersions2",setter="setImageVersions2")
     * @ORM\OneToMany(targetEntity="Media", mappedBy="item", cascade={"persist"})
     */
    private $imageVersions2;

    /**
     * @JMS\Type("ArrayCollection<App\Entity\Media>")
     * @JMS\Accessor(getter="getVideoVersions",setter="setVideoVersions")
     * @ORM\OneToMany(targetEntity="Media", mappedBy="videoItem", cascade={"persist"})
     */
    private $videoVersions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $has_audio;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $video_duration;

    /**
     * @JMS\Type("ArrayCollection<App\Entity\Item>")
     * @JMS\Accessor(getter="getCarouselMedia",setter="setCarouselMedia")
     * @ORM\OneToMany(targetEntity="Item", mappedBy="carousel_parent", cascade={"persist"})
     */
    private $carousel_media;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="carousel_media")
     * @ORM\JoinColumn(name="carousel_parent_id", referencedColumnName="id")
     */
    private $carousel_parent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $original_width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $original_height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lng;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_pk", referencedColumnName="pk")
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true, options={ "collation": "utf8mb4_general_ci" })
     */
    private $caption;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $like_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $comment_count;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="item")
     */
    private $comments;

    public function __construct()
    {
        $this->carousel_media = new ArrayCollection();
        $this->imageVersions2 = new ArrayCollection();
        $this->videoVersions = new ArrayCollection();
    }

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
        return $this->takenAt;
    }

    /**
     * @param mixed $takenAt
     * @return Item
     */
    public function setTakenAt($takenAt)
    {
        $this->takenAt = $takenAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceTimestamp()
    {
        return $this->deviceTimestamp;
    }

    /**
     * @param mixed $deviceTimestamp
     * @return Item
     */
    public function setDeviceTimestamp($deviceTimestamp)
    {
        $this->deviceTimestamp = $deviceTimestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * @param mixed $mediaType
     * @return Item
     */
    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
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
        return $this->clientCacheKey;
    }

    /**
     * @param mixed $clientCacheKey
     * @return Item
     */
    public function setClientCacheKey($clientCacheKey)
    {
        $this->clientCacheKey = $clientCacheKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * @param mixed $filterType
     * @return Item
     */
    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getImageVersions2(): ?Collection
    {
        return $this->imageVersions2;
    }

    /**
     * @param Collection $imageVersions2
     * @return Item
     */
    public function setImageVersions2(Collection $imageVersions2)
    {
        array_map(function (Media $media) {
            $media->setItem($this);
        }, $imageVersions2->toArray());
        $this->imageVersions2 = $imageVersions2;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getVideoVersions(): ?Collection
    {
        return $this->videoVersions;
    }

    /**
     * @param Collection $videoVersions
     * @return Item
     */
    public function setVideoVersions(Collection $videoVersions)
    {
        array_map(function(Media $media) {
            $media->setVideoItem($this);
        }, $videoVersions->toArray());
        $this->videoVersions = $videoVersions;
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
    public function getCarouselMedia(): ?   Collection
    {
        return $this->carousel_media;
    }

    /**
     * @param mixed $carousel_media
     * @return Item
     */
    public function setCarouselMedia(Collection $carousel_media)
    {
        array_map(function (Item $item) {
            $item->setCarouselParent($this);
        }, $carousel_media->toArray());
        $this->carousel_media = $carousel_media;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarouselParent()
    {
        return $this->carousel_parent;
    }

    /**
     * @param mixed $carousel_parent
     * @return Item
     */
    public function setCarouselParent($carousel_parent)
    {
        $this->carousel_parent = $carousel_parent;
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

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     * @return Item
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }
}
