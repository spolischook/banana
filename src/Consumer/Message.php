<?php

namespace App\Consumer;

class Message
{
    public $type;
    public $data;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
