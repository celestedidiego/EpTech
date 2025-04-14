<?php

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FItemOrder::class)]
#[ORM\Table('itemorder')]
class EItemOrder {

    ##[ORM\Id]
    ##[ORM\GeneratedValue()]
    ##[ORM\Column(type:"integer")]
    #private int $idItemOrder;

    #[ORM\Column(type:'integer')]
    private $quantity;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity:EOrder::class, inversedBy:'itemOrder')]
    #[ORM\JoinColumn(name:'order_id', referencedColumnName:'idOrder')]
    private ?EOrder $order = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity:EProduct::class, inversedBy:'itemOrder')]
    #[ORM\JoinColumn(name:'product_id', referencedColumnName:'productId')]
    private ?EProduct $product = null;

    // Manca lo stato dell'ordine 

    
    public function __construct() {
        $this->quantity = 0;
        //$this->orderId = $orderId;
        //$this->productId = $productId;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    #public function getIdItemOrder(): int
    #{
    #    return $this->idItemOrder;
    #}

    public function getOrder(): EOrder
    {
        return $this->order;
    }

    public function setOrder(?EOrder $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getProduct(): ?EProduct
    {
        return $this->product;
    }

    public function setProduct(?EProduct $product): self
    {
        $this->product= $product;
        return $this;
    }
}
?>