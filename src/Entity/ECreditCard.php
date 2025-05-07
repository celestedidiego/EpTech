<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:FCreditCard::class)]
#[ORM\Table(name: "credit_card")]
class ECreditCard 
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 16, nullable: false)]
    private string $cardNumber;

    #[ORM\Column(type: "string", length: 100, nullable: false)]
    private string $cardHolderName;

    #[ORM\Column(type: "string", length: 5, nullable: false)]
    private string $endDate;

    #[ORM\Column(type: "string", length: 3, nullable: false)]
    private string $cvv;

    #[ORM\Column(type: 'boolean')]
    private $is_deleted = false;

    #[ORM\OneToMany(targetEntity: EOrder::class, mappedBy:'creditCard')]
    private Collection $orders;

    #[ORM\ManyToOne(targetEntity: ERegisteredUser::class, inversedBy:'creditcards')]
    #[ORM\JoinColumn(name:'registered_user_id', referencedColumnName:'registeredUserId', nullable:true)]
    private ?ERegisteredUser $registeredUser = null;

    public function __construct($cardNumber = null, $cardHolderName = null, $endDate = null, $cvv = null)
    {
        if ($cardHolderName !== null) { 
            $this->cardNumber = $cardNumber;
            $this->cardHolderName = $cardHolderName;
            $this->setEndDate($endDate);
            $this->cvv = $cvv;
        }
    }
    
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    public function getCardHolderName(): mixed
    {
        return $this->cardHolderName;
    }

    public function setCardHolderName(string $cardHolderName): static
    {
        $this->cardHolderName = $cardHolderName;
        return $this;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }
    
    public function setEndDate($endDate): void
    {
        // Valida il formato di endDate (MM/YY)
        if (preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $endDate)) {
            $this->endDate = $endDate;
        } else {
            throw new InvalidArgumentException('Invalid end date format. Use MM/YY.');
        }
    }

    public function getCVV(): mixed
    {
        return $this->cvv;
    }

    public function setCVV($cvv): mixed
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders($orders)
    {
        $this->orders = $orders;
        return $this;
    }

    public function getRegisteredUser()
    {
        return $this->registeredUser;
    }
    
    public function setRegisteredUser($registeredUser)
    {
        $this->registeredUser = $registeredUser;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->is_deleted = $deleted;
        return $this;
    }

}