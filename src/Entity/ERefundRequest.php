<?php

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "refund_requests")]
class ERefundRequest {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: EOrder::class)]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "idOrder", nullable: false)]
    private EOrder $order;

    #[ORM\Column(type: "string", length: 20)]
    private string $status;

    #[ORM\Column(type: "datetime")]
    private \DateTime $requestDate;

    public function __construct(EOrder $order) {
        $this->order = $order;
        $this->status = 'in attesa';
        $this->requestDate = new \DateTime();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getOrder(): EOrder {
        return $this->order;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function setStatus(string $status): void {
        $this->status = $status;
    }

    public function getRequestDate(): \DateTime {
        return $this->requestDate;
    }
    
}