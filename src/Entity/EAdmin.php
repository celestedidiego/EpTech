<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:FAdmin::class)]
#[ORM\Table(name:"admin")]

class EAdmin
{

    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private int $adminId;

    #[ORM\Column(type: "string", length: 255)]
    protected $name;

    #[ORM\Column(type: "string", length: 255)]
    protected $surname;

    #[ORM\Column(type: "string", length: 255)]
    protected $email;

    #[ORM\Column(type: "string", length: 255)]
    protected $username;

    #[ORM\Column(type: "string", length: 255)]
    protected $password;

    #[ORM\OneToMany(targetEntity: EProduct::class, mappedBy: 'admin')]
    private Collection $products;

    #[ORM\OneToMany(targetEntity: EReview::class, mappedBy: 'admin')]
    private Collection $reviews;

    public function __construct($name, $surname, $email, $username, $password)
    {
        //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        //$this->password = $hashedPassword;
        $this->products = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getIdAdmin(): int
    {
        return $this->adminId;
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

    public function getProducts()
    {
        return $this->products;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }
}
?>