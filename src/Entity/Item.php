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
     * @ORM\OneToMany(targetEntity="Media", mappedBy="item")
     */
    private $image_versions2;

    /**
     * @ORM\OneToOne(targetEntity="Media")
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
     * @ORM\OneToMany(targetEntity="Item", mappedBy="carousel_parent_id")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
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
}
