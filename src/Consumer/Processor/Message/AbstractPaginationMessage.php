<?php

namespace App\Consumer\Processor\Message;

abstract class AbstractPaginationMessage implements MessageInterface
{
    /**
     * @var null|int
     */
    protected $pageNumber = null;

    /**
     * @var null|string
     */
    protected $maxId;

    /**
     * @return int|null
     */
    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    /**
     * @param int|null $pageNumber
     * @return self
     */
    public function setPageNumber(?int $pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    public function decreasePageNumber()
    {
        if (null === $this->getPageNumber()) {
            return $this;
        }

        --$this->pageNumber;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMaxId(): ?string
    {
        return $this->maxId;
    }

    /**
     * @param null|string $maxId
     * @return self
     */
    public function setMaxId(?string $maxId): self
    {
        $this->maxId = $maxId;
        return $this;
    }
}
