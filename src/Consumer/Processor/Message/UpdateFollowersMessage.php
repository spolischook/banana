<?php

namespace App\Consumer\Processor\Message;

use App\Consumer\Message;

class UpdateFollowersMessage implements MessageInterface
{
    public $type = 'update_followers';

}
