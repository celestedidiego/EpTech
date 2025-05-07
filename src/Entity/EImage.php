<?php
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:FImage::class)]
#[ORM\Table('image')]
class EImage{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $idImage = null;

    #[ORM\Column(type: 'string', length:70, columnDefinition: 'VARCHAR(70)')]
    private $name;

    #[ORM\Column(type: 'integer', columnDefinition: 'INT(9)')]
    private $size;

    #[ORM\Column(type: 'string', length:20, columnDefinition: 'VARCHAR(20)')]
    private $type;

    #[ORM\Column(type: 'blob')]
    private $imageData;

    #[ORM\ManyToOne(targetEntity: EProduct::class, inversedBy:'images')]
    #[ORM\JoinColumn(name:'productId', referencedColumnName:'productId', nullable:true)]
    private EProduct|null $product = null;

    public function __construct($name, $size, $type, $imageData){
        $this->name = $name;
        $this->size = $size;
        $this->type = $type;
        $this->imageData = $imageData;
    }

    public function getEncodedData() {
        if (is_resource($this->imageData)) {
            rewind($this->imageData); // Riavvia il puntatore del flusso
            $data = stream_get_contents($this->imageData);
            return $data;
        } else {
            return $this->imageData;
        }
    }

    /**
     * Get the value of id_image
     */
    public function getIdImage()
    {
        return $this->idImage;
    }

    /**
     * Set the value of id_image
     */
    public function setIdImage($idImage)
    {
        $this->idImage = $idImage;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get the value of imageData
     */
    public function getImageData()
    {
        return $this->imageData;
    }

    /**
     * Set the value of imageData
     */
    public function setImageData($imageData)
    {
        $this->imageData = $imageData;
    }

    /**
     * Get the value of productId
     */
    public function getProduct(): ?EProduct
    {
        return $this->product;
    }

    /**
     * Set the value of productId
     */
    public function setProduct(?EProduct $product)
    {
        $this->product = $product;
    }
    
}