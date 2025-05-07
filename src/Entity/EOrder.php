<?php

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class EOrder
 * @ORM\Entity(repositoryClass=FOrder::class)
 * @ORM\Table(name="orders")
 * Represents an order entity containing details about the order, its items, and associated entities.
 * @package EpTech\Entity
 */
class EOrder 
{
    /**
     * @var int The unique identifier of the order.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $idOrder;

    /**
     * @var float The total price of the order.
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private float $totalPrice;

    /**
     * @var \DateTime The date and time when the order was created.
     * @ORM\Column(type="datetime")
     */
    private \DateTime $dateTime;

    /**
     * @var string The status of the order.
     * @ORM\Column(type="string", length=50, columnDefinition="VARCHAR(50)")
     */
    private $orderStatus;

    /**
     * @var int The total quantity of products in the order.
     * @ORM\Column(type="integer")
     */
    private $qTotalProduct;

    /**
     * @var EShipping|null The shipping method associated with the order.
     * @ORM\ManyToOne(targetEntity=EShipping::class, inversedBy="orders")
     * @ORM\JoinColumn(name="shipping_id", referencedColumnName="idShipping", nullable=false)
     */
    private ?EShipping $shipping = null;

    /**
     * @var Collection|EItemOrder[] The collection of items in the order.
     * @ORM\OneToMany(mappedBy="order", targetEntity=EItemOrder::class)
     */
    private Collection $itemOrder;

    /**
     * @var ECreditCard|null The credit card used for the order.
     * @ORM\ManyToOne(targetEntity=ECreditCard::class)
     * @ORM\JoinColumn(name="card_number", referencedColumnName="cardNumber")
     */
    private ?ECreditCard $creditCard = null;

    /**
     * @var ERegisteredUser|null The registered user who placed the order.
     * @ORM\ManyToOne(targetEntity=ERegisteredUser::class, inversedBy="orders")
     * @ORM\JoinColumn(name="registered_user_id", referencedColumnName="registeredUserId", nullable=false)
     */
    private ?ERegisteredUser $registeredUser = null;

    /**
     * @var Collection|ERefundRequest[] The collection of refund requests associated with the order.
     * @ORM\OneToMany(mappedBy="order", targetEntity=ERefundRequest::class)
     */
    private Collection $refundRequests;

    /**
     * Constructor for the EOrder class.
     * Initializes default values for the order.
     */
    public function __construct()
    {
        $this->totalPrice = 0.0;
        $this->dateTime = new \DateTime();
        $this->orderStatus = 'In elaborazione';
        $this->qTotalProduct = 0;
        $this->itemOrder = new ArrayCollection();
        $this->refundRequests = new ArrayCollection();
    }

    /**
     * Adds an item to the order.
     * @param EItemOrder $itemOrder The item to add.
     * @return void
     */
    public function addQProductOrder(EItemOrder $itemOrder)
    {
        if (!$this->itemOrder->contains($itemOrder)) {
            $this->itemOrder[] = $itemOrder;
            $itemOrder->setOrder($this);
        }
    }

    /**
     * Removes an item from the order.
     * @param EItemOrder $itemOrder The item to remove.
     * @return void
     */
    public function removeQProductOrder(EItemOrder $itemOrder)
    {
        if ($this->itemOrder->removeElement($itemOrder)) {
            // set the owning side to null (unless already changed)
            if ($itemOrder->getOrder() === $this) {
                $itemOrder->setOrder(null);
            }
        }
    }

    /**
     * Returns the unique identifier of the order.
     * @return int The unique identifier of the order.
     */
    public function getIdOrder(): int
    {
        return $this->idOrder;
    }

    /**
     * Sets the unique identifier of the order.
     * @param int $idOrder The unique identifier of the order.
     * @return void
     */
    public function setIdOrder($idOrder): void
    {
        $this->idOrder = $idOrder;
    }

    /**
     * Returns the total price of the order.
     * @return mixed The total price of the order.
     */
    public function getTotalPrice(): mixed
    {
        return $this->totalPrice;
    }

    /**
     * Sets the total price of the order.
     * @param mixed $totalPrice The total price of the order.
     * @return void
     */
    public function setTotalPrice($totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * Returns the date and time of the order.
     * @return \DateTime The date and time of the order.
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * Sets the date and time of the order.
     * @param \DateTime $dateTime The date and time of the order.
     * @return static
     */
    public function setDateTime(\DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Returns the shipping method associated with the order.
     * @return EShipping|null The shipping method.
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Sets the shipping method associated with the order.
     * @param EShipping $shipping The shipping method.
     * @return $this
     */
    public function setShipping(EShipping $shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    /**
     * Returns the total quantity of products in the order.
     * @return int The total quantity of products.
     */
    public function getTotalQuantityProduct()
    {
        return $this->qTotalProduct;
    }

    /**
     * Sets the total quantity of products in the order.
     * @param int $qTotalProduct The total quantity of products.
     * @return $this
     */
    public function setTotalQuantityProduct($qTotalProduct)
    {
        $this->qTotalProduct = $qTotalProduct;
        return $this;
    }

    /**
     * Returns the status of the order.
     * @return string The status of the order.
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Sets the status of the order.
     * @param string $orderStatus The status of the order.
     * @return void
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * Checks if the order is marked as "Preso in carico".
     * @return bool True if the order is "Preso in carico", false otherwise.
     */
    public function isPresoInCarico()
    {
        return $this->orderStatus == 'Preso in carico';   
    }

    /**
     * Checks if the order is marked as "In spedizione".
     * @return bool True if the order is "In spedizione", false otherwise.
     */
    public function isInSpedizione()
    {
        return $this->orderStatus == 'In spedizione';
    }

    /**
     * Checks if the order is marked as "Consegnato".
     * @return bool True if the order is "Consegnato", false otherwise.
     */
    public function isConsegnato()
    {
        return $this->orderStatus == 'Consegnato';
    }

    /**
     * Checks if the order is marked as "Annullato".
     * @return bool True if the order is "Annullato", false otherwise.
     */
    public function isAnnullato()
    {
        return $this->orderStatus == 'Annullato';
    }
        
    /**
     * Returns the collection of items in the order.
     * @return Collection|EItemOrder[] The collection of items.
     */
    public function getItemOrder(): Collection
    {
        return $this->itemOrder;
    }

    /**
     * Sets the collection of items in the order.
     * @param Collection $itemOrder The collection of items.
     * @return void
     */
    public function setItemOrder(Collection $itemOrder): void
    {
        $this->itemOrder = $itemOrder;
    }

    /**
     * Returns the credit card used for the order.
     * @return ECreditCard|null The credit card.
     */
    public function getCreditCard(): ?ECreditCard
    {
        return $this->creditCard;
    }

    /**
     * Sets the credit card used for the order.
     * @param ECreditCard|null $creditCard The credit card.
     * @return void
     */
    public function setCreditCard(?ECreditCard $creditCard): void
    {
        $this->creditCard = $creditCard;
    }

    /**
     * Returns the registered user who placed the order.
     * @return ERegisteredUser|null The registered user.
     */
    public function getRegistereduser()
    {
        return $this->registeredUser;
    }

    /**
     * Sets the registered user who placed the order.
     * @param ERegisteredUser $registeredUser The registered user.
     * @return void
     */
    public function setRegisteredUser($registeredUser): void
    {
        $this->registeredUser = $registeredUser;
    }

    /**
     * Returns the collection of refund requests associated with the order.
     * @return array The collection of refund requests.
     */
    public function getRefundRequests(): array
    {
        return $this->refundRequests->toArray();
    }

    /**
     * Checks if the order has any refund requests.
     * @return bool True if there are refund requests, false otherwise.
     */
    public function hasRefundRequest(): bool
    {
        foreach ($this->refundRequests as $refundRequest) {
            if ($refundRequest->getStatus() !== null) {
                return true;
            }
        }
        return false;
    }
}
