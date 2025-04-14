<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FCategory::class)]
#[ORM\Table(name: "category")]
class ECategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private int $categoryId;

    #[ORM\Column(type: "string", length: 100, columnDefinition: "VARCHAR(100) NOT NULL UNIQUE")]
    private string $nameCategory;

    //Relazione OneToMany con EProduct
    #[ORM\OneToMany(targetEntity:EProduct::class, mappedBy:'category')]
    private Collection $products;

    public function __construct(string $nameCategory)
    {
        $this->nameCategory = $nameCategory;
        $this->products = new ArrayCollection();
    }

    public function getIdCategory(): ?int
    {
        return $this->categoryId;
    }

    public function getNameCategory(): string
    {
        return $this->nameCategory;
    }

    public function setNameCategory($nameCategory): void
    {
        $this->nameCategory = $nameCategory;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts($products)
    {
        $this->products = $products;
    }
}