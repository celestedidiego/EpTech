<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:FAdmin::class)]
#[ORM\Table(name:"admin")]
class EAdmin
{
    /**
     * Primary key for the admin entity.
     * @var int $adminId
     */
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: "adminId",type: "integer")]
    private int $adminId;

    /**
     * Admin's first name.
     * @var string $name
     */
    #[ORM\Column(name: "name",type: "string", length: 255)]
    protected $name;

    /**
     * Admin's surname.
     * @var string $surname
     */
    #[ORM\Column(name:"surname",type: "string", length: 255)]
    protected $surname;

    /**
     * Admin's email address.
     * @var string $email
     */
    #[ORM\Column(name: "email",type: "string", length: 255)]
    protected $email;

    /**
     * Admin's username for login.
     * @var string $username
     */
    #[ORM\Column(name:"username",type: "string", length: 255)]
    protected $username;

    /**
     * Admin's password for login.
     * @var string $password
     */
    #[ORM\Column(name:"password",type: "string", length: 255)]
    protected $password;

    /**
     * Collection of products managed by the admin.
     * @var Collection<int, EProduct>
     */
    #[ORM\OneToMany(targetEntity: EProduct::class, mappedBy: 'admin')]
    private Collection $products;

    /**
     * Collection of reviews managed by the admin.
     * @var Collection<int, EReview>
     */
    #[ORM\OneToMany(targetEntity: EReview::class, mappedBy: 'admin')]
    private Collection $reviews;

    /**
     * Constructor to initialize attributes and collections.
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param string $username
     * @param string $password
     */
    public function __construct($name, $surname, $email, $username, $password)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->products = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    /**
     * Get the admin ID.
     * @return int  
     */
    public function getIdAdmin(): int
    {
        return $this->adminId;
    }

    /**
     * Get the admin's name.
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the admin's name.
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get the admin's surname.
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * Set the admin's surname.
     * @param string $surname
     */
    public function setSurname($surname) {
        $this->surname = $surname;
    }

    /**
     * Get the admin's email.
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set the admin's email.
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Get the admin's username.
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set the admin's username.
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Get the admin's password.
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set the admin's password.
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Get the collection of products managed by the admin.
     * @return Collection<int, EProduct>
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Get the collection of reviews managed by the admin.
     * @return Collection<int, EReview>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }
}
?>