<?php

namespace App\Consumer\Processor\Message;

class UpdateItemMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $itemId;

    /**
     * @return string
     */
    public function getItemId(): string
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     * @return UpdateItemMessage
     */
    public function setItemId(string $itemId): UpdateItemMessage
    {
        $this->itemId = $itemId;
        return $this;
    }
}
