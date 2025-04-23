<?php

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FReview::class)]
#[ORM\Table(name: 'reviews')]
class EReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $idReview;

    #[ORM\Column(type: 'string', columnDefinition:'TEXT')]
    private string $text;

    #[ORM\Column(type: 'integer', columnDefinition:'INT(5)')]
    private int $vote;

    #[ORM\Column(type: 'text', nullable: true)]
    private $responseAdmin;

    #[ORM\ManyToOne(targetEntity: 'EProduct', inversedBy: 'reviews')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'productId', nullable:false)]
    private EProduct $product;

    #[ORM\ManyToOne(targetEntity:ERegisteredUser::class, inversedBy:'reviews')]
    #[ORM\JoinColumn(name:'registere_user_id', referencedColumnName:'registeredUserId', nullable:false)]
    private ERegisteredUser $registeredUser;

    #[ORM\ManyToOne(targetEntity:EAdmin::class, inversedBy:'reviews')]
    #[ORM\JoinColumn(name:'adminId', referencedColumnName:'adminId', nullable:true)]
    private EAdmin|null $admin = null;

    public function __construct($text, $vote)
    {
        //DA TOGLIERE?
    }

    public function getReviewId(): int
    {
        return $this->idReview;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getVote(): int
    {
        return $this->vote;
    }

    public function setVote(int $vote): void
    {
        $this->vote = $vote;
    }

    public function getProduct(): ?EProduct
    {
        return $this->product;
    }

    public function setProduct(?EProduct $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getRegisteredUser(): ERegisteredUser
    {
        return $this->registeredUser;
    }

    public function setRegisteredUser(ERegisteredUser $registeredUser): void
    {
        $this->registeredUser = $registeredUser;
    }

    public function setResponseAdmin($response, EAdmin $adminId) {
        $this->responseAdmin = $response;
        $this->setAdmin($adminId); 
    }
    public function getResponseAdmin() {
        return $this->responseAdmin;
    }

    public function getAdmin(): ?EAdmin
    {
        return $this->admin;
    }

    public function setAdmin(?EAdmin $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

}
?>