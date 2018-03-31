<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserLikeEventRepository")
 */
class UserLikeEvent extends UserEvent
{
    const TYPE_LIKE = 'like';
    const TYPE_DISLIKE = 'dislike';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $likeDirection;

    public function getId()
    {
        return $this->id;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return string
     */
    public function getLikeDirection(): string
    {
        return $this->likeDirection;
    }

    /**
     * @param string $likeDirection
     * @return UserLikeEvent
     */
    public function setLikeDirection(string $likeDirection): UserLikeEvent
    {
        $this->likeDirection = $likeDirection;
        return $this;
    }
}
