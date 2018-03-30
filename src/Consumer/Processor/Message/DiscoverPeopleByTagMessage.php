<?php

namespace App\Consumer\Processor\Message;

class DiscoverPeopleByTagMessage extends AbstractPaginationMessage implements FeedIdInterface
{
    /**
     * @var string
     */
    protected $tag;


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

    public function getFeedId()
    {
        return $this->getTag();
    }
}
