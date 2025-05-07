<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EUnRegisteredUser
 * @ORM\Entity
 * @ORM\Table(name="unregistered")
 * Represents an unregistered user in the system.
 * @package EpTech\Entity
 */
class EUnRegisteredUser
{
    /**
     * @var int The unique identifier of the unregistered user.
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $UnRegisteredUserId;

    /**
     * Constructor for the EUnRegisteredUser class.
     * Initializes the unregistered user entity.
     */
    public function __construct()
    {

    }

    /**
     * Returns the unique identifier of the unregistered user.
     * @return int The unique identifier of the unregistered user.
     */
    public function getIdUnRegisteredUser(): int
    {
        return $this->UnRegisteredUserId;
    }
}
?>