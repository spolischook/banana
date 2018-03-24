<?php

namespace App\Consumer\Processor\Message;

use InstagramAPI\Signatures;

class UpdateFollowersMessage implements MessageInterface
{
    /**
     * @var string|null
     */
    protected $maxId = null;

    /**
     * @var string[]
     */
    protected $followers = [];

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
     * @return UpdateFollowersMessage
     */
    public function setMaxId(?string $maxId): UpdateFollowersMessage
    {
        $this->maxId = $maxId;
        return $this;
    }

    public function addFollower(string $id)
    {
        $this->followers[] = $id;
        return $this;
    }

    public function getFollowers()
    {
        return $this->followers;
    }

    public function getRankToken()
    {
        return $this->rankToken;
    }
}
