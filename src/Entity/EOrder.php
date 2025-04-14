<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: FOrder::class)]
#[ORM\Table(name: "orders")]
class EOrder 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $idOrder;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $totalPrice;

    #[ORM\Column(type: "datetime")]
    private \DateTime $dateTime;

    #[ORM\Column(type: 'string', length:50, columnDefinition: 'VARCHAR(50)')]
    private $orderStatus;

    #[ORM\Column(type: 'integer')]
    private $qTotalProduct;

    #[ORM\ManyToOne(targetEntity: EShipping::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name:"shipping_id", referencedColumnName:"idShipping", nullable:false)]
    private ?EShipping $shipping = null;

    #[ORM\OneToMany(mappedBy:"order", targetEntity: EItemOrder::class)]
    private Collection $itemOrder;

    #[ORM\ManyToOne(targetEntity: 'ECreditCard')]
    #[ORM\JoinColumn(name: 'card_number', referencedColumnName: 'cardNumber')]
    private ?ECreditCard $creditCard = null;

    #[ORM\ManyToOne(targetEntity:ERegisteredUser::class, inversedBy:"orders")]
    #[ORM\JoinColumn(name:"registered_user_id", referencedColumnName:"registeredUserId", nullable:false)]
    private ?ERegisteredUser $registeredUser = null;

    public function __construct()
    {
        $this->totalPrice = 0.0;
        $this->dateTime = new \DateTime();
        $this->orderStatus = 'In elaborazione';
        $this->qTotalProduct = 0;
        $this->itemOrder = new ArrayCollection();
        //$this->creditCard = $creditCard;
        //$this->registeredUser = $registeredUser;
        //$this->shipping = $shipping;
    }

    public function addQProductOrder(EItemOrder $itemOrder){
        if (!$this->itemOrder->contains($itemOrder)) {
            $this->itemOrder[] = $itemOrder;
            $itemOrder->setOrder($this);
        }
    }

    public function removeQProductOrder(EItemOrder $itemOrder){
        if ($this->itemOrder->removeElement($itemOrder)) {
            // set the owning side to null (unless already changed)
            if ($itemOrder->getOrder() === $this) {
                $itemOrder->setOrder(null);
            }
        }
    }

    public function getIdOrder(): int
    {
        return $this->idOrder;
    }

    public function setIdOrder($idOrder): void
    {
        $this->idOrder = $idOrder;
    }

    public function getTotalPrice(): mixed
    {
        return $this->totalPrice;
    }

    public function setTotalPrice($totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getShipping()
    {
        return $this->shipping;
    }

    public function setShipping(EShipping $shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    public function getTotalQuantityProduct()
    {
        return $this->qTotalProduct;
    }

    public function setTotalQuantityProduct($qTotalProduct)
    {
        $this->qTotalProduct = $qTotalProduct;
        return $this;
    }

    public function getOrderStatus() {
        return $this->orderStatus;
    }

    public function setOrderStatus($orderStatus) {
        $this->orderStatus = $orderStatus;
    }

    public function isPresoInCarico() {
        return $this->orderStatus== 'Preso in carico';   
    }

     public function isInSpedizione(){
        return $this->orderStatus == 'In spedizione';
    }

    public function isConsegnato(){
        return $this->orderStatus == 'Consegnato';
    }

    public function isAnnullato(){
        return $this->orderStatus == 'Annullato';
    }
        
    public function getItemOrder(): Collection
    {
        return $this->itemOrder;
    }

    public function setItemOrder(Collection $itemOrder): void
    {
        $this->itemOrder = $itemOrder;
    }

    public function getCreditCard(): ?ECreditCard
    {
        return $this->creditCard;
    }

    public function setCreditCard(?ECreditCard $creditCard): void
    {
        $this->creditCard = $creditCard;
    }

    public function getRegistereduser()
    {
        return $this->registeredUser;
    }

    public function setRegisteredUser($registeredUser): void
    {
        $this->registeredUser = $registeredUser;
    }
}
?>