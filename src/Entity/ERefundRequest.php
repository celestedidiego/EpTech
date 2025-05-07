<?php

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ERefundRequest
 * @ORM\Entity
 * @ORM\Table(name="refund_requests")
 * Represents a refund request associated with an order.
 * @package EpTech\Entity
 */
class ERefundRequest {
    /**
     * @var int The unique identifier of the refund request.
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var EOrder The order associated with the refund request.
     * @ORM\ManyToOne(targetEntity=EOrder::class)
     * @ORM\JoinColumn(name="order_id", referencedColumnName="idOrder", nullable=false)
     */
    private EOrder $order;

    /**
     * @var string The status of the refund request (e.g., pending, approved, rejected).
     * @ORM\Column(type="string", length=20)
     */
    private string $status;

    /**
     * @var \DateTime The date when the refund request was created.
     * @ORM\Column(type="datetime")
     */
    private \DateTime $requestDate;

    /**
     * Constructor for the ERefundRequest class.
     * Initializes the refund request with the associated order and default values.
     * @param EOrder $order The associated order.
     */
    public function __construct(EOrder $order) {
        $this->order = $order;
        $this->status = 'pending';
        $this->requestDate = new \DateTime();
    }

    /**
     * Returns the unique identifier of the refund request.
     * @return int The unique identifier of the refund request.
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns the order associated with the refund request.
     * @return EOrder The associated order.
     */
    public function getOrder(): EOrder {
        return $this->order;
    }

    /**
     * Returns the status of the refund request.
     * @return string The status of the refund request.
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * Sets the status of the refund request.
     * @param string $status The status of the refund request.
     * @return void
     */
    public function setStatus(string $status): void {
        $this->status = $status;
    }

    /**
     * Returns the date when the refund request was created.
     * @return \DateTime The creation date of the refund request.
     */
    public function getRequestDate(): \DateTime {
        return $this->requestDate;
    }
}