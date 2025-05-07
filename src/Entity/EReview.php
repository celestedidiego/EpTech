<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Class EReview
 * @ORM\Entity(repositoryClass=FReview::class)
 * @ORM\Table(name="reviews")
 * Represents a review written by a registered user for a product, with optional admin response.
 * @package EpTech\Entity
 */
class EReview
{
    /**
     * @var int The unique identifier of the review.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $idReview;

    /**
     * @var string The text content of the review.
     * @ORM\Column(type="string", columnDefinition="TEXT")
     */
    private string $text;

    /**
     * @var int The vote or rating given in the review.
     * @ORM\Column(type="integer", columnDefinition="INT(5)")
     */
    private int $vote;

    /**
     * @var string|null The response from an admin to the review.
     * @ORM\Column(type="text", nullable=true)
     */
    private $responseAdmin;

    /**
     * @var EProduct The product associated with the review.
     * @ORM\ManyToOne(targetEntity=EProduct::class, inversedBy="reviews")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="productId", nullable=false)
     */
    private EProduct $product;

    /**
     * @var ERegisteredUser The registered user who wrote the review.
     * @ORM\ManyToOne(targetEntity=ERegisteredUser::class, inversedBy="reviews")
     * @ORM\JoinColumn(name="registere_user_id", referencedColumnName="registeredUserId", nullable=false)
     */
    private ERegisteredUser $registeredUser;

    /**
     * @var EAdmin|null The admin who responded to the review.
     * @ORM\ManyToOne(targetEntity=EAdmin::class, inversedBy="reviews")
     * @ORM\JoinColumn(name="adminId", referencedColumnName="adminId", nullable=true)
     */
    private EAdmin|null $admin = null;

    /**
     * Constructor for the EReview class.
     * Initializes the review entity.
     */
    public function __construct()
    {
    
    }

    /**
     * Returns the unique identifier of the review.
     * @return int The unique identifier of the review.
     */
    public function getReviewId(): int
    {
        return $this->idReview;
    }

    /**
     * Returns the text content of the review.
     * @return string The text content of the review.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text content of the review.
     * @param string $text The text content of the review.
     * @return void
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * Returns the vote or rating given in the review.
     * @return int The vote or rating.
     */
    public function getVote(): int
    {
        return $this->vote;
    }

    /**
     * Sets the vote or rating given in the review.
     * @param int $vote The vote or rating.
     * @return void
     */
    public function setVote(int $vote): void
    {
        $this->vote = $vote;
    }

    /**
     * Returns the product associated with the review.
     * @return EProduct|null The associated product.
     */
    public function getProduct(): ?EProduct
    {
        return $this->product;
    }

    /**
     * Sets the product associated with the review.
     * @param EProduct|null $product The associated product.
     * @return self
     */
    public function setProduct(?EProduct $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Returns the registered user who wrote the review.
     * @return ERegisteredUser The registered user.
     */
    public function getRegisteredUser(): ERegisteredUser
    {
        return $this->registeredUser;
    }

    /**
     * Sets the registered user who wrote the review.
     * @param ERegisteredUser $registeredUser The registered user.
     * @return void
     */
    public function setRegisteredUser(ERegisteredUser $registeredUser): void
    {
        $this->registeredUser = $registeredUser;
    }

    /**
     * Sets the admin response to the review.
     * @param string $response The response text.
     * @param EAdmin $adminId The admin who responded.
     * @return void
     */
    public function setResponseAdmin($response, EAdmin $adminId): void
    {
        $this->responseAdmin = $response;
        $this->setAdmin($adminId); 
    }

    /**
     * Returns the admin response to the review.
     * @return string|null The admin response.
     */
    public function getResponseAdmin()
    {
        return $this->responseAdmin;
    }

    /**
     * Returns the admin who responded to the review.
     * @return EAdmin|null The responding admin.
     */
    public function getAdmin(): ?EAdmin
    {
        return $this->admin;
    }

    /**
     * Sets the admin who responded to the review.
     * @param EAdmin|null $admin The responding admin.
     * @return self
     */
    public function setAdmin(?EAdmin $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

}
?>