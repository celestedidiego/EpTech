<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EItemOrder
 * @ORM\Entity(repositoryClass=FItemOrder::class)
 * @ORM\Table(name="itemorder")
 * Represents an item in an order, linking a product to an order with a specified quantity.
 * @package EpTech\Entity
 */
class EItemOrder {

    /**
     * @var int The quantity of the product in the order.
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var EOrder|null The order associated with this item.
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=EOrder::class, inversedBy="itemOrder")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="idOrder")
     */
    private ?EOrder $order = null;

    /**
     * @var EProduct|null The product associated with this item.
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity=EProduct::class, inversedBy="itemOrder")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="productId")
     */
    private ?EProduct $product = null;

    /**
     * Constructor for the EItemOrder class.
     * Initializes the quantity to 0.
     */
    public function __construct() {
        $this->quantity = 0;
    }

    /**
     * Returns the quantity of the product in the order.
     * @return int The quantity of the product.
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Sets the quantity of the product in the order.
     * @param int $quantity The quantity of the product.
     * @return self
     */
    public function setQuantity($quantity): self {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Returns the order associated with this item.
     * @return EOrder The associated order.
     */
    public function getOrder(): EOrder {
        return $this->order;
    }

    /**
     * Sets the order associated with this item.
     * @param EOrder|null $order The associated order.
     * @return self
     */
    public function setOrder(?EOrder $order): self {
        $this->order = $order;
        return $this;
    }

    /**
     * Returns the product associated with this item.
     * @return EProduct|null The associated product.
     */
    public function getProduct(): ?EProduct {
        return $this->product;
    }

    /**
     * Sets the product associated with this item.
     * @param EProduct|null $product The associated product.
     * @return self
     */
    public function setProduct(?EProduct $product): self {
        $this->product = $product;
        return $this;
    }
}