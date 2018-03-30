<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserFollowEventRepository")
 */
class UserFollowEvent extends UserEvent
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $followingStatus;

    public function getFollowingStatus(): ?bool
    {
        return $this->followingStatus;
    }

    public function setFollowingStatus(bool $followingStatus): self
    {
        $this->followingStatus = $followingStatus;

        return $this;
    }
}
