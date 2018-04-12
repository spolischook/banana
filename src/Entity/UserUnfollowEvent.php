<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserUnfollowEventRepository")
 */
class UserUnfollowEvent extends UserEvent
{
}
