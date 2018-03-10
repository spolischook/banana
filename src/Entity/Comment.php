<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $pk;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string")
     */
    private $content_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $did_report_as_spam;

    /**
     * @ORM\ManyToOne(targetEntity="Media", inversedBy="comments")
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_pk", referencedColumnName="pk")
     */
    private $user;
}
