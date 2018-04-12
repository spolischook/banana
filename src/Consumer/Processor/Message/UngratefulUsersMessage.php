<?php

namespace App\Consumer\Processor\Message;

class UngratefulUsersMessage implements MessageInterface
{
    protected $unActiveDays = 7;
    protected $maxProcessing = 300;

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

    /**
     * @return int
     */
    public function getMaxProcessing(): int
    {
        return $this->maxProcessing;
    }

    /**
     * @param int $maxProcessing
     * @return UngratefulUsersMessage
     */
    public function setMaxProcessing(int $maxProcessing): UngratefulUsersMessage
    {
        $this->maxProcessing = $maxProcessing;
        return $this;
    }

    public function decreaseProcessingCount()
    {
        --$this->maxProcessing;
    }
}
