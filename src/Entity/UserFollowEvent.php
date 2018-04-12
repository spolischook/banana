<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserFollowEventRepository")
 */
class UserFollowEvent extends UserEvent
{
}
