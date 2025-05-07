<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EShipping
 * @ORM\Entity(repositoryClass=FShipping::class)
 * @ORM\Table(name="shipping")
 * Represents a shipping address associated with orders and a registered user.
 * @package EpTech\Entity
 */
class EShipping
{
    /**
     * @var int The unique identifier of the shipping address.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $idShipping;

    /**
     * @var string The address of the shipping destination.
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @var string The postal code (CAP) of the shipping destination.
     * @ORM\Column(type="string", length=10)
     */
    private string $cap;

    /**
     * @var string The city of the shipping destination.
     * @ORM\Column(type="string", length=100)
     */
    private string $city;

    /**
     * @var string The recipient's first name.
     * @ORM\Column(type="string", length=100)
     */
    private string $recipientName;

    /**
     * @var string The recipient's surname.
     * @ORM\Column(type="string", length=100)
     */
    private string $recipientSurname;

    /**
     * @var bool Indicates if the shipping address is marked as deleted.
     * @ORM\Column(type="boolean")
     */
    private $is_deleted = false;

    /**
     * @var Collection|EOrder[] The collection of orders associated with this shipping address.
     * @ORM\OneToMany(targetEntity=EOrder::class, mappedBy="shipping")
     */
    private Collection $orders;

    /**
     * @var ERegisteredUser|null The registered user associated with this shipping address.
     * @ORM\ManyToOne(targetEntity=ERegisteredUser::class, inversedBy="shippings")
     * @ORM\JoinColumn(name="registered_user_id", referencedColumnName="registeredUserId", nullable=false)
     */
    private ?ERegisteredUser $shippingRegisteredUser = null;

    /**
     * Constructor for the EShipping class.
     * Initializes the shipping address with the provided details and an empty collection of orders.
     * @param string $address The address of the shipping destination.
     * @param string $cap The postal code (CAP) of the shipping destination.
     * @param string $city The city of the shipping destination.
     * @param string $recipientName The recipient's first name.
     * @param string $recipientSurname The recipient's surname.
     */
    public function __construct(string $address, string $cap, string $city, string $recipientName, string $recipientSurname)
    {
        $this->address = $address;
        $this->cap = $cap;
        $this->city = $city;
        $this->recipientName = $recipientName;
        $this->recipientSurname = $recipientSurname;
        $this->orders = new ArrayCollection();
    }

    /**
     * Returns the unique identifier of the shipping address.
     * @return int The unique identifier of the shipping address.
     */
    public function getIdShipping(): int
    {
        return $this->idShipping;
    }

    /**
     * Checks if the shipping address is marked as deleted.
     * @return bool True if the address is deleted, false otherwise.
     */
    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    /**
     * Marks the shipping address as deleted or not.
     * @param bool $deleted True to mark as deleted, false otherwise.
     * @return self
     */
    public function setDeleted(bool $deleted): self
    {
        $this->is_deleted = $deleted;
        return $this;
    }

    /**
     * Returns the address of the shipping destination.
     * @return string The address of the shipping destination.
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Sets the address of the shipping destination.
     * @param string $address The address of the shipping destination.
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * Returns the postal code (CAP) of the shipping destination.
     * @return string The postal code (CAP).
     */
    public function getCAP(): string
    {
        return $this->cap;
    }

    /**
     * Sets the postal code (CAP) of the shipping destination.
     * @param string $cap The postal code (CAP).
     * @return void
     */
    public function setCAP(string $cap): void
    {
        $this->cap = $cap;
    }

    /**
     * Returns the city of the shipping destination.
     * @return string The city of the shipping destination.
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Sets the city of the shipping destination.
     * @param string $city The city of the shipping destination.
     * @return void
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * Returns the recipient's first name.
     * @return string The recipient's first name.
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * Sets the recipient's first name.
     * @param string $recipientName The recipient's first name.
     * @return void
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;
    }

    /**
     * Returns the recipient's surname.
     * @return string The recipient's surname.
     */
    public function getRecipientSurname()
    {
        return $this->recipientSurname;
    }

    /**
     * Sets the recipient's surname.
     * @param string $recipientSurname The recipient's surname.
     * @return void
     */
    public function setRecipientSurname($recipientSurname)
    {
        $this->recipientSurname = $recipientSurname;
    }

    /**
     * Returns the collection of orders associated with this shipping address.
     * @return Collection|EOrder[] The collection of orders.
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Sets the collection of orders associated with this shipping address.
     * @param Collection $orders The collection of orders.
     * @return self
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * Returns the registered user associated with this shipping address.
     * @return ERegisteredUser|null The associated registered user.
     */
    public function getShippingRegisteredUser(): ?ERegisteredUser 
    {
        return $this->shippingRegisteredUser;
    }

    /**
     * Sets the registered user associated with this shipping address.
     * @param ERegisteredUser|null $shippingRegisteredUser The associated registered user.
     * @return void
     */
    public function setShippingRegisteredUser(?ERegisteredUser $shippingRegisteredUser)
    {
        $this->shippingRegisteredUser = $shippingRegisteredUser;
    }

}