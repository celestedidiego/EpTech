<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ECategory
 * @ORM\Entity(repositoryClass=FCategory::class)
 * @ORM\Table(name="category")
 * Represents a product category.
 * @package EpTech\Entity
 */
class ECategory
{
    /**
     * @var int Unique identifier for the category.
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $categoryId;

    /**
     * @var string Name of the category.
     * @ORM\Column(type="string", length=100, columnDefinition="VARCHAR(100) NOT NULL UNIQUE")
     */
    private string $nameCategory;

    /**
     * @var Collection|EProduct[] Collection of products associated with the category.
     * @ORM\OneToMany(targetEntity=EProduct::class, mappedBy="category")
     */
    private Collection $products;

    /**
     * Constructor for the ECategory class.
     * @param string $nameCategory Name of the category.
     */
    public function __construct(string $nameCategory)
    {
        $this->nameCategory = $nameCategory;
        $this->products = new ArrayCollection();
    }

    /**
     * Returns the unique identifier of the category.
     * @return int|null Unique identifier of the category.
     */
    public function getIdCategory(): ?int
    {
        return $this->categoryId;
    }

    /**
     * Returns the name of the category.
     * @return string Name of the category.
     */
    public function getNameCategory(): string
    {
        return $this->nameCategory;
    }

    /**
     * Sets the name of the category.
     * @param string $nameCategory Name of the category.
     * @return void
     */
    public function setNameCategory(string $nameCategory): void
    {
        $this->nameCategory = $nameCategory;
    }

    /**
     * Returns the collection of products associated with the category.
     * @return Collection|EProduct[] Collection of products.
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Sets the collection of products associated with the category.
     * @param Collection|EProduct[] $products Collection of products.
     * @return void
     */
    public function setProducts(Collection $products): void
    {
        $this->products = $products;
    }
}