<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $pk;

    /**
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $full_name;

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
     * @ORM\Column(type="string")
     */
    private $profile_pic_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $media_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $follower_count;

    /**
     * @ORM\Column(type="integer")
     */
    private $following_count;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_business;

    /**
     * @ORM\Column(type="string")
     */
    private $biography;

    /**
     * @ORM\Column(type="string")
     */
    private $external_url;

    /**
     * @ORM\Column(type="integer")
     */
    private $usertags_count;
}
