<?php

namespace App\Consumer\Processor\Message;

use InstagramAPI\Signatures;

abstract class AbstractUserPaginationMessage implements MessageInterface
{
    /**
     * @var string|null
     */
    protected $maxId = null;

    /**
     * @var string[]
     */
    protected $users = [];

    protected $rankToken;

    public function __construct()
    {
        $this->rankToken = Signatures::generateUUID();
    }

    /**
     * @return string
     */
    public function getMaxId(): ?string
    {
        return $this->maxId;
    }

    /**
     * @param string $maxId
     * @return self
     */
    public function setMaxId(?string $maxId): self
    {
        $this->maxId = $maxId;
        return $this;
    }

    public function addUser(string $id)
    {
        $this->users[] = $id;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getRankToken()
    {
        return $this->rankToken;
    }
}
