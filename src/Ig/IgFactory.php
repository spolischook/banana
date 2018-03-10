<?php

namespace App\Ig;

use InstagramAPI\Instagram;

class IgFactory
{
    private $username;
    private $password;
    private $debug;
    private $truncatedDebug;

    public function __construct(string $username, string $password, $debug = false, $truncatedDebug = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->debug = $debug;
        $this->truncatedDebug = $truncatedDebug;
    }

    public function login(): Instagram
    {
        $ig = new Instagram($this->debug, $this->truncatedDebug);
        $ig->login($this->username, $this->password);

        return $ig;
    }
}
