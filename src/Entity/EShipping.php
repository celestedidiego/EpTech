<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FShipping::class)]
#[ORM\Table(name: "shipping")]
class EShipping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idShipping;

    #[ORM\Column(type: "string", length: 255)]
    private string $address;

    #[ORM\Column(type: "string", length: 10)]
    private string $cap;

    #[ORM\Column(type: "string", length: 100)]
    private string $city;

    #[ORM\Column(type: "string", length: 100)]
    private string $recipientName;

    #[ORM\Column(type: "string", length: 100)]
    private string $recipientSurname;

    #[ORM\Column(type: 'boolean')]
    private $is_deleted = false;

    #[ORM\OneToMany(targetEntity:EOrder::class, mappedBy:'shipping')]
    private Collection $orders;

    #[ORM\ManyToOne(targetEntity: ERegisteredUser::class, inversedBy:'shippings')]
    #[ORM\JoinColumn(name:'registered_user_id', referencedColumnName:'registeredUserId', nullable:false)]
    private ?ERegisteredUser $shippingRegisteredUser = null;

    public function __construct(string $address, string $cap, string $city, string $recipientName, string $recipientSurname)
    {
        $this->address = $address;
        $this->cap = $cap;
        $this->city = $city;
        $this->recipientName = $recipientName;
        $this->recipientSurname = $recipientSurname;
        $this->orders = new ArrayCollection();
    }

    public function getIdShipping(): int
    {
        return $this->idShipping;
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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCAP(): string
    {
        return $this->cap;
    }

    public function setCAP(string $cap): void
    {
        $this->cap = $cap;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getRecipientName()
    {
        return $this->recipientName;
    }

    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    public function getRecipientSurname()
    {
        return $this->recipientSurname;
    }

    public function setRecipientSurname($recipientSurname)
    {
        $this->recipientSurname = $recipientSurname;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    public function getShippingRegisteredUser(): ?ERegisteredUser 
    {
        return $this->shippingRegisteredUser;
    }

    public function setShippingRegisteredUser(?ERegisteredUser $shippingRegisteredUser)
    {
        $this->shippingRegisteredUser = $shippingRegisteredUser;
    }

}