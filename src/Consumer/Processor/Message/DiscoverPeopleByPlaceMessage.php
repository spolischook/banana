<?php

namespace App\Consumer\Processor\Message;

class DiscoverPeopleByPlaceMessage extends AbstractPaginationMessage implements FeedIdInterface
{
    /**
     * @var string
     */
    protected $locationId;

    /**
     * @return string
     */
    public function getLocationId(): string
    {
        return $this->locationId;
    }

    /**
     * @param string $locationId
     * @return DiscoverPeopleByPlaceMessage
     */
    public function setLocationId(string $locationId): DiscoverPeopleByPlaceMessage
    {
        $this->locationId = $locationId;
        return $this;
    }

    public function getFeedId()
    {
        return $this->getLocationId();
    }
}
