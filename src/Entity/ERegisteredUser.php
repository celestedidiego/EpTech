<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ERegisteredUser
 * @ORM\Entity(repositoryClass=FRegisteredUser::class)
 * @ORM\Table(name="registered_user")
 * Represents a registered user with personal details, orders, reviews, addresses, and credit cards.
 * @package EpTech\Entity
 */
class ERegisteredUser
{
    /**
     * @var int The unique identifier of the registered user.
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $registeredUserId;

    /**
     * @var string The first name of the user.
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string The surname of the user.
     * @ORM\Column(type="string", length=255)
     */
    protected $surname;

    /**
     * @var string The email address of the user.
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @var \DateTime The birth date of the user.
     * @ORM\Column(type="date")
     */
    protected \DateTime $birthDate;

    /**
     * @var string The username of the user.
     * @ORM\Column(type="string", length=255)
     */
    protected $username;

    /**
     * @var string The password of the user.
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @var bool Indicates if the user is blocked.
     * @ORM\Column(type="boolean")
     */
    private $is_blocked = false;

    /**
     * @var bool Indicates if the user is marked as deleted.
     * @ORM\Column(type="boolean")
     */
    private $is_deleted = false;

    /**
     * @var Collection|EOrder[] The collection of orders placed by the user.
     * @ORM\OneToMany(targetEntity=EOrder::class, mappedBy="registeredUser", cascade={"persist", "remove"})
     */
    private Collection $orders;

    /**
     * @var Collection|EReview[] The collection of reviews written by the user.
     * @ORM\OneToMany(targetEntity=EReview::class, mappedBy="registeredUser")
     */
    private Collection $reviews;

    /**
     * @var Collection|EShipping[] The collection of addresses associated with the user.
     * @ORM\OneToMany(targetEntity=EShipping::class, mappedBy="registeredUser")
     */
    private Collection $addresses;

    /**
     * @var Collection|ECreditCard[] The collection of credit cards associated with the user.
     * @ORM\OneToMany(targetEntity=ECreditCard::class, mappedBy="registeredUser")
     */
    private Collection $creditCards;

    /**
     * @var string|null The confirmation token for email verification.
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $confirmationToken = null;

    /**
     * @var bool Indicates if the user's email is confirmed.
     * @ORM\Column(type="boolean")
     */
    private bool $emailConfirmed = false;

    /**
     * Constructor for the ERegisteredUser class.
     * Initializes the user with personal details and empty collections for relationships.
     * @param string $name The first name of the user.
     * @param string $surname The surname of the user.
     * @param string $email The email address of the user.
     * @param \DateTime $birthDate The birth date of the user.
     * @param string $username The username of the user.
     * @param string $password The password of the user.
     */
    public function __construct($name, $surname, $email, $birthDate, $username, $password)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->birthDate = $birthDate;
        $this->username = $username;
        $this->password = $password;
        $this->orders = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->creditCards = new ArrayCollection();
    }

    /**
     * Returns the unique identifier of the registered user.
     * @return int The unique identifier of the user.
     */
    public function getIdRegisteredUser(): int
    {
        return $this->registeredUserId;
    }

    /**
     * Returns the first name of the user.
     * @return string The first name of the user.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the first name of the user.
     * @param string $name The first name of the user.
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the surname of the user.
     * @return string The surname of the user.
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Sets the surname of the user.
     * @param string $surname The surname of the user.
     * @return void
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Returns the email address of the user.
     * @return string The email address of the user.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email address of the user.
     * @param string $email The email address of the user.
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns the birth date of the user.
     * @return \DateTime The birth date of the user.
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Sets the birth date of the user.
     * @param \DateTime $birthDate The birth date of the user.
     * @return void
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * Returns the username of the user.
     * @return string The username of the user.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the username of the user.
     * @param string $username The username of the user.
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Returns the password of the user.
     * @return string The password of the user.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the password of the user.
     * @param string $password The password of the user.
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the collection of orders placed by the user.
     * @return Collection|EOrder[] The collection of orders.
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * Sets the collection of orders placed by the user.
     * @param Collection $orders The collection of orders.
     * @return void
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * Returns the collection of reviews written by the user.
     * @return Collection|EReview[] The collection of reviews.
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * Sets the collection of reviews written by the user.
     * @param Collection $reviews The collection of reviews.
     * @return self
     */
    public function setReviews(Collection $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    /**
     * Returns the collection of addresses associated with the user.
     * @return Collection|EShipping[] The collection of addresses.
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * Returns the collection of credit cards associated with the user.
     * @return Collection|ECreditCard[] The collection of credit cards.
     */
    public function getCreditCards(): Collection
    {
        return $this->creditCards;
    }

    /**
     * Sets the collection of credit cards associated with the user.
     * @param Collection $creditCards The collection of credit cards.
     * @return self
     */
    public function setCreditCards(Collection $creditCards): self
    {
        $this->creditCards = $creditCards;

        return $this;
    }

    /**
     * Checks if the user is blocked.
     * @return bool True if the user is blocked, false otherwise.
     */
    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    /**
     * Sets the blocked status of the user.
     * @param bool $blocked True to block the user, false otherwise.
     * @return void
     */
    public function setBlocked(bool $blocked)
    {
        $this->is_blocked = $blocked;
    }

    /**
     * Checks if the user is marked as deleted.
     * @return bool True if the user is deleted, false otherwise.
     */
    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    /**
     * Sets the deleted status of the user.
     * @param bool $deleted True to mark as deleted, false otherwise.
     * @return void
     */
    public function setDeleted(bool $deleted)
    {
        $this->is_deleted = $deleted;
    }

    /**
     * Returns the confirmation token for email verification.
     * @return string|null The confirmation token.
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * Sets the confirmation token for email verification.
     * @param string|null $confirmationToken The confirmation token.
     * @return void
     */
    public function setConfirmationToken(?string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * Checks if the user's email is confirmed.
     * @return bool True if the email is confirmed, false otherwise.
     */
    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    /**
     * Sets the email confirmation status of the user.
     * @param bool $emailConfirmed True to confirm the email, false otherwise.
     * @return void
     */
    public function setEmailConfirmed(bool $emailConfirmed): void
    {
        $this->emailConfirmed = $emailConfirmed;
    }
}