<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EProduct
 * @ORM\Entity(repositoryClass=FProduct::class)
 * @ORM\Table(name="products")
 * Represents a product entity with details such as name, price, description, and relationships.
 * @package EpTech\Entity
 */
class EProduct
{
    /**
     * @var int The unique identifier of the product.
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $productId;

    /**
     * @var string The name of the product.
     * @ORM\Column(type="string", length=255, columnDefinition="VARCHAR(255) NOT NULL")
     */
    private string $nameProduct;

    /**
     * @var float The price of the product.
     * @ORM\Column(type="decimal", precision=10, scale=2, columnDefinition="DECIMAL(10,2) NOT NULL CHECK(priceProduct >= 0)")
     */
    private float $priceProduct;

    /**
     * @var string The description of the product.
     * @ORM\Column(type="text", columnDefinition="TEXT NOT NULL")
     */
    private string $description;

    /**
     * @var string The brand of the product.
     * @ORM\Column(type="string", length=255, columnDefinition="VARCHAR(255) NOT NULL")
     */
    private string $brand;

    /**
     * @var string|null The model of the product.
     * @ORM\Column(type="string", length=255, nullable=true, columnDefinition="VARCHAR(255)")
     */
    private $model;

    /**
     * @var string|null The color of the product.
     * @ORM\Column(type="string", length=50, nullable=true, columnDefinition="VARCHAR(50)")
     */
    private $color;

    /**
     * @var bool Indicates if the product is marked as deleted.
     * @ORM\Column(type="boolean")
     */
    private $is_deleted = false;

    /**
     * @var float The available quantity of the product.
     * @ORM\Column(type="decimal", precision=10, scale=0, columnDefinition="DECIMAL(10,0) NOT NULL CHECK(avQuantity >= 0)")
     */
    private float $avQuantity;

    /**
     * @var ECategory|null The category associated with the product.
     * @ORM\ManyToOne(targetEntity=ECategory::class, inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="categoryId", nullable=true, onDelete="SET NULL")
     */
    private ?ECategory $category = null;

    /**
     * @var Collection|EItemOrder[] The collection of item orders associated with the product.
     * @ORM\OneToMany(mappedBy="product", targetEntity=EItemOrder::class)
     */
    private Collection $itemOrder;

    /**
     * @var Collection|EReview[] The collection of reviews associated with the product.
     * @ORM\OneToMany(targetEntity=EReview::class, mappedBy="product")
     */
    private Collection $reviews;

    /**
     * @var EAdmin The admin associated with the product.
     * @ORM\ManyToOne(targetEntity=EAdmin::class, inversedBy="products")
     * @ORM\JoinColumn(name="adminId", referencedColumnName="adminId", nullable=false, onDelete="CASCADE")
     */
    private EAdmin $admin;

    /**
     * @var Collection|EImage[] The collection of images associated with the product.
     * @ORM\OneToMany(targetEntity=EImage::class, mappedBy="product")
     */
    private Collection $images;

    /**
     * Constructor for the EProduct class.
     * Initializes the product with the provided details and empty collections for relationships.
     * @param string $nameProduct The name of the product.
     * @param float $priceProduct The price of the product.
     * @param string $description The description of the product.
     * @param string $brand The brand of the product.
     * @param string $model The model of the product.
     * @param string $color The color of the product.
     * @param float $avQuantity The available quantity of the product.
     */
    public function __construct(string $nameProduct, float $priceProduct, string $description, string $brand, string $model, string $color, $avQuantity)
    {
        $this->nameProduct = $nameProduct;
        $this->priceProduct = $priceProduct;
        $this->description = $description;
        $this->brand = $brand;
        $this->model = $model;
        $this->color = $color;
        $this->avQuantity = $avQuantity;
        $this->itemOrder = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * Returns the unique identifier of the product.
     * @return int The unique identifier of the product.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Returns the category associated with the product.
     * @return ECategory|null The associated category.
     */
    public function getNameCategory(): ?ECategory
    {
        return $this->category;
    }

    /**
     * Sets the category associated with the product.
     * @param ECategory|null $category The associated category.
     * @return self
     */
    public function setNameCategory(?ECategory $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Returns the name of the product.
     * @return string The name of the product.
     */
    public function getNameProduct(): string
    {
        return $this->nameProduct;
    }

    /**
     * Sets the name of the product.
     * @param string $nameProduct The name of the product.
     * @return self
     */
    public function setNameProduct(string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;
        return $this;
    }

    /**
     * Returns the price of the product.
     * @return float The price of the product.
     */
    public function getPriceProduct(): float
    {
        return $this->priceProduct;
    }

    /**
     * Sets the price of the product.
     * @param float $priceProduct The price of the product.
     * @return self
     */
    public function setPriceProduct(float $priceProduct): self
    {
        $this->priceProduct = $priceProduct;
        return $this;
    }

    /**
     * Returns the description of the product.
     * @return string The description of the product.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the description of the product.
     * @param string $description The description of the product.
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns the brand of the product.
     * @return string The brand of the product.
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * Sets the brand of the product.
     * @param string $brand The brand of the product.
     * @return self
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * Returns the model of the product.
     * @return string|null The model of the product.
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Sets the model of the product.
     * @param string|null $model The model of the product.
     * @return self
     */
    public function setModel(?string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Returns the color of the product.
     * @return string|null The color of the product.
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Sets the color of the product.
     * @param string|null $color The color of the product.
     * @return self
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Returns the collection of item orders associated with the product.
     * @return Collection|EItemOrder[] The collection of item orders.
     */
    public function getItemOrder()
    {
        return $this->itemOrder;
    }

    /**
     * Returns the collection of reviews associated with the product.
     * @return Collection|EReview[] The collection of reviews.
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * Sets the collection of reviews associated with the product.
     * @param Collection $reviews The collection of reviews.
     * @return void
     */
    public function setReviews(Collection $reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * Returns the available quantity of the product.
     * @return float The available quantity.
     */
    public function getAvQuantity()
    {
        return $this->avQuantity;
    }

    /**
     * Sets the available quantity of the product.
     * @param float $avQuantity The available quantity.
     * @return void
     */
    public function setAvQuantity($avQuantity)
    {
        $this->avQuantity = $avQuantity;
    }

    /**
     * Returns the admin associated with the product.
     * @return EAdmin The associated admin.
     */
    public function getAdmin(): EAdmin
    {
        return $this->admin;
    }

    /**
     * Sets the admin associated with the product.
     * @param EAdmin $admin The associated admin.
     * @return self
     */
    public function setAdmin(EAdmin $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Checks if the product is marked as deleted.
     * @return bool True if the product is deleted, false otherwise.
     */
    public function isDeleted(): bool
    {
        return $this->is_deleted;
    }

    /**
     * Marks the product as deleted or not.
     * @param bool $deleted True to mark as deleted, false otherwise.
     * @return self
     */
    public function setDeleted(bool $deleted): self
    {
        $this->is_deleted = $deleted;
        return $this;
    }

    /**
     * Returns the collection of images associated with the product.
     * @return Collection|EImage[] The collection of images.
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * Adds an image to the product.
     * @param EImage $image The image to add.
     * @return self
     */
    public function addImage(EImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    /**
     * Removes an image from the product.
     * @param EImage $image The image to remove.
     * @return self
     */
    public function removeImage(EImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // Set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }
}
