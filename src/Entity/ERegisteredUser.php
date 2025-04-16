<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:FRegisteredUser::class)]
#[ORM\Table(name:"registered_user")]

class ERegisteredUser
{

    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private int $registeredUserId;

    #[ORM\Column(type: "string", length: 255)]
    protected $name;

    #[ORM\Column(type: "string", length: 255)]
    protected $surname;

    #[ORM\Column(type: "string", length: 255)]
    protected $email;

    #[ORM\Column(type: "date")]
    protected \DateTime $birthDate;

    #[ORM\Column(type: "string", length: 255)]
    protected $username;

    #[ORM\Column(type: "string", length: 255)]
    protected $password;

    #[ORM\Column(type: 'boolean')]
    private $is_blocked = false;

    #[ORM\Column(type: 'boolean')]
    private $is_deleted = false;

    #[ORM\OneToMany(targetEntity:EOrder::class, mappedBy:"registeredUser", cascade:["persist", "remove"])]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity:EReview::class, mappedBy:'registeredUser')]
    private Collection $reviews;

    #[ORM\OneToMany(targetEntity:EShipping::class, mappedBy:'registeredUser')]
    private Collection $addresses;

    #[ORM\OneToMany(targetEntity:ECreditCard::class, mappedBy:'registeredUser')]
    private Collection $creditCards;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\Column(type: 'boolean')]
    private bool $emailConfirmed = false;

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

    public function getIdRegisteredUser(): int
    {
        return $this->registeredUserId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders($orders) 
    {
        $this->orders = $orders;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function setReviews(Collection $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function getCreditCards(): Collection
    {
        return $this->creditCards;
    }

    public function setCreditCards(Collection $creditCards): self
    {
        $this->creditCards= $creditCards;

        return $this;
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    public function setBlocked(bool $blocked)
    {
        $this->is_blocked = $blocked;
    }
    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    public function setDeleted(bool $deleted)
    {
        $this->is_deleted = $deleted;
    }

    public function getConfirmationToken(): ?string {
        return $this->confirmationToken;
    }
    
    public function setConfirmationToken(?string $confirmationToken): void {
        $this->confirmationToken = $confirmationToken;
    }
    
    public function isEmailConfirmed(): bool {
        return $this->emailConfirmed;
    }
    
    public function setEmailConfirmed(bool $emailConfirmed): void {
        $this->emailConfirmed = $emailConfirmed;
    }

}