<?php

namespace App\Consumer\Processor\Message;

class UpdateMyFeedMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $maxId = null;

    /**
     * @var int
     */
    protected $pageNumber = null;

    /**
     * @return string
     */
    public function getMaxId(): ?string
    {
        return $this->maxId;
    }

    /**
     * @param string $maxId
     * @return UpdateMyFeedMessage
     */
    public function setMaxId(string $maxId): UpdateMyFeedMessage
    {
        $this->maxId = $maxId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    /**
     * @param int $pageNumber
     * @return UpdateMyFeedMessage
     */
    public function setPageNumber(int $pageNumber): UpdateMyFeedMessage
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    public function decreasePageNumber()
    {
        if (null === $this->getPageNumber()) {
            return;
        }

        --$this->pageNumber;
    }
}
