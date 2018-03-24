<?php

namespace App\Consumer\Processor\Message;

class TouchUserMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return TouchUserMessage
     */
    public function setUserId(string $userId): TouchUserMessage
    {
        $this->userId = $userId;
        return $this;
    }
}
