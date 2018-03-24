<?php

namespace App\Consumer\Processor\Message;

class DiscoverPeopleByTagMessage implements MessageInterface
{
    /**
     * @var null|int
     */
    protected $pageNumber = null;

    /**
     * @var string
     */
    protected $tag;

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
     * @return DiscoverPeopleByTagMessage
     */
    public function setPageNumber(?int $pageNumber): DiscoverPeopleByTagMessage
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
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return DiscoverPeopleByTagMessage
     */
    public function setTag(string $tag): DiscoverPeopleByTagMessage
    {
        $this->tag = $tag;
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
     * @return DiscoverPeopleByTagMessage
     */
    public function setMaxId(?string $maxId): DiscoverPeopleByTagMessage
    {
        $this->maxId = $maxId;
        return $this;
    }
}
