<?php

namespace App\Ig;

use InstagramAPI\Instagram;

class IgSingleton
{
    private $username;
    private $password;
    private $debug;
    private $truncatedDebug;
    private $ig;

    public function __construct(string $username, string $password, $debug = false, $truncatedDebug = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->debug = $debug;
        $this->truncatedDebug = $truncatedDebug;
    }

    public function getIg()
    {
        if (!$this->ig) {
            $this->ig = $this->login();
        }

        return $this->ig;
    }

    private function login(): Instagram
    {
        Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
        $ig = new Instagram($this->debug, $this->truncatedDebug);
        $ig->login($this->username, $this->password);

        return $ig;
    }
}
