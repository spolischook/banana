<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserTypeEventRepository")
 */
class UserTypeEvent extends UserEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $oldType;

    /**
     * @ORM\Column(type="integer")
     */
    private $newType;

    public function getId()
    {
        return $this->id;
    }

    public function getOldType(): ?int
    {
        return $this->oldType;
    }

    public function setOldType(int $oldType): self
    {
        $this->oldType = $oldType;

        return $this;
    }

    public function getNewType(): ?int
    {
        return $this->newType;
    }

    public function setNewType($newType): self
    {
        $this->newType = $newType;
        return $this;
    }
}
