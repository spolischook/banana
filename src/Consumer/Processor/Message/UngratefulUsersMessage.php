<?php

namespace App\Consumer\Processor\Message;

class UngratefulUsersMessage implements MessageInterface
{
    protected $unActiveDays = 7;

    /**
     * @return int
     */
    public function getUnActiveDays()
    {
        return $this->unActiveDays;
    }

    /**
     * @param int $unActiveDays
     * @return UngratefulUsersMessage
     */
    public function setUnActiveDays(int $unActiveDays)
    {
        $this->unActiveDays = $unActiveDays;
        return $this;
    }
}
