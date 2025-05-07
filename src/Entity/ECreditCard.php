<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ECreditCard
 * @ORM\Entity(repositoryClass=FCreditCard::class)
 * @ORM\Table(name="credit_card")
 * Represents a credit card entity.
 * @package EpTech\Entity
 */
class ECreditCard 
{
    /**
     * @var string The credit card number (16 digits).
     * @ORM\Id
     * @ORM\Column(type="string", length=16, nullable=false)
     */
    private string $cardNumber;

    /**
     * @var string The name of the cardholder.
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private string $cardHolderName;

    /**
     * @var string The expiration date of the card in MM/YY format.
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private string $endDate;

    /**
     * @var string The CVV code of the card (3 digits).
     * @ORM\Column(type="string", length=3, nullable=false)
     */
    private string $cvv;

    /**
     * @var bool Indicates if the card is deleted.
     * @ORM\Column(type="boolean")
     */
    private $is_deleted = false;

    /**
     * @var Collection|EOrder[] Collection of orders associated with the card.
     * @ORM\OneToMany(targetEntity=EOrder::class, mappedBy="creditCard")
     */
    private Collection $orders;

    /**
     * @var ERegisteredUser|null The registered user associated with the card.
     * @ORM\ManyToOne(targetEntity=ERegisteredUser::class, inversedBy="creditcards")
     * @ORM\JoinColumn(name="registered_user_id", referencedColumnName="registeredUserId", nullable=true)
     */
    private ?ERegisteredUser $registeredUser = null;

    /**
     * Constructor for the ECreditCard class.
     * @param string|null $cardNumber The credit card number.
     * @param string|null $cardHolderName The name of the cardholder.
     * @param string|null $endDate The expiration date of the card.
     * @param string|null $cvv The CVV code of the card.
     */
    public function __construct($cardNumber = null, $cardHolderName = null, $endDate = null, $cvv = null)
    {
        if ($cardHolderName !== null) { 
            $this->cardNumber = $cardNumber;
            $this->cardHolderName = $cardHolderName;
            $this->setEndDate($endDate);
            $this->cvv = $cvv;
        }
    }
    
    /**
     * Returns the credit card number.
     * @return string The credit card number.
     */
    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    /**
     * Sets the credit card number.
     * @param string $cardNumber The credit card number.
     * @return static
     */
    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * Returns the name of the cardholder.
     * @return mixed The name of the cardholder.
     */
    public function getCardHolderName(): mixed
    {
        return $this->cardHolderName;
    }

    /**
     * Sets the name of the cardholder.
     * @param string $cardHolderName The name of the cardholder.
     * @return static
     */
    public function setCardHolderName(string $cardHolderName): static
    {
        $this->cardHolderName = $cardHolderName;
        return $this;
    }

    /**
     * Returns the expiration date of the card.
     * @return string The expiration date in MM/YY format.
     */
    public function getEndDate(): string
    {
        return $this->endDate;
    }
    
    /**
     * Sets the expiration date of the card.
     * @param string $endDate The expiration date in MM/YY format.
     * @throws InvalidArgumentException If the format is invalid.
     * @return void
     */
    public function setEndDate($endDate): void
    {
        // Valida il formato di endDate (MM/YY)
        if (preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $endDate)) {
            $this->endDate = $endDate;
        } else {
            throw new InvalidArgumentException('Invalid end date format. Use MM/YY.');
        }
    }

    /**
     * Returns the CVV code of the card.
     * @return mixed The CVV code.
     */
    public function getCVV(): mixed
    {
        return $this->cvv;
    }

    /**
     * Sets the CVV code of the card.
     * @param mixed $cvv The CVV code.
     * @return $this
     */
    public function setCVV($cvv): mixed
    {
        $this->cvv = $cvv;
        return $this;
    }

    /**
     * Returns the collection of orders associated with the card.
     * @return Collection|EOrder[] The collection of orders.
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * Sets the collection of orders associated with the card.
     * @param Collection|EOrder[] $orders The collection of orders.
     * @return $this
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
        return $this;
    }

    /**
     * Returns the registered user associated with the card.
     * @return ERegisteredUser|null The registered user.
     */
    public function getRegisteredUser()
    {
        return $this->registeredUser;
    }
    
    /**
     * Sets the registered user associated with the card.
     * @param ERegisteredUser|null $registeredUser The registered user.
     * @return $this
     */
    public function setRegisteredUser($registeredUser)
    {
        $this->registeredUser = $registeredUser;

        return $this;
    }

    /**
     * Checks if the card is marked as deleted.
     * @return bool True if the card is deleted, false otherwise.
     */
    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    /**
     * Marks the card as deleted or not.
     * @param bool $deleted True to mark as deleted, false otherwise.
     * @return $this
     */
    public function setDeleted(bool $deleted): self
    {
        $this->is_deleted = $deleted;
        return $this;
    }
}