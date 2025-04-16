<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:FProduct::class)]
#[ORM\Table(name: "products")]
class EProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private int $productId;

    #[ORM\Column(type: "string", length: 255, columnDefinition: "VARCHAR(255) NOT NULL")]
    private string $nameProduct;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, columnDefinition: "DECIMAL(10,2) NOT NULL CHECK(priceProduct >= 0)")]
    private float $priceProduct;

    #[ORM\Column(type: "text", columnDefinition: "TEXT NOT NULL")]
    private string $description;

    #[ORM\Column(type: "string", length: 255, columnDefinition: "VARCHAR(255) NOT NULL")]
    private string $brand;

    #[ORM\Column(type: "string", length: 255, nullable: true, columnDefinition: "VARCHAR(255)")]
    private $model;

    #[ORM\Column(type: "string", length: 50, nullable: true, columnDefinition: "VARCHAR(50)")]
    private $color;

    #[ORM\Column(type: 'boolean')]
    private $is_deleted = false;

    #[ORM\Column(type:"decimal", precision: 10, scale: 0, columnDefinition: "DECIMAL(10,0) NOT NULL CHECK(avQuantity >= 0)")]
    private float $avQuantity;

    #[ORM\ManyToOne(targetEntity: ECategory::class, inversedBy:'products')]
    #[ORM\JoinColumn(name:'category_id', referencedColumnName:'categoryId', nullable:true, onDelete:'SET NULL')]
    private ?ECategory $category = null;

    #[ORM\OneToMany(mappedBy:"product", targetEntity: EItemOrder::class)]
    private Collection $itemOrder;

    #[ORM\OneToMany(targetEntity:EReview::class, mappedBy:'product')]
    private Collection $reviews;

    #[ORM\ManyToOne(targetEntity: EAdmin::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'adminId', referencedColumnName: 'adminId', nullable: false, onDelete: 'CASCADE')] //prima nullable era true e onDelete era NOT NULL
    private EAdmin $admin;

    #[ORM\OneToMany(targetEntity:EImage::class, mappedBy:'product')]
    private Collection $images;

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

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getNameCategory(): ?ECategory
    {
        return $this->category;
    }

    public function setNameCategory(?ECategory $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getNameProduct(): string
    {
        return $this->nameProduct;
    }

    public function setNameProduct(string $nameProduct): self
    {
        $this->nameProduct = $nameProduct;
        return $this;
    }

    public function getPriceProduct(): float
    {
        return $this->priceProduct;
    }

    public function setPriceProduct(float $priceProduct): self
    {
        $this->priceProduct = $priceProduct;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function getItemOrder()
    {
        return $this->itemOrder;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function setReviews(Collection $reviews)
    {
        $this->reviews= $reviews;
    }

    public function getAvQuantity()
    {
        return $this->avQuantity;
    }

    public function setAvQuantity($avQuantity)
    {
        $this->avQuantity = $avQuantity;
    }

    public function getAdmin(): EAdmin
    {
        return $this->admin;
    }

    public function setAdmin(EAdmin $admin): self
    {
        $this->admin = $admin;
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

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(EImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

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
