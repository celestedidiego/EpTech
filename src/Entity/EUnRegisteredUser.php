<?php

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name:'unregistered')]
class EUnRegisteredUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private int $UnRegisteredUserId;
    public function __construct(){

    }

    public function getIdUnRegisteredUser(): int
    {
        return $this->UnRegisteredUserId;
    }
}
?>