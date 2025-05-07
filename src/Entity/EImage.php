<?php
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EImage
 * @ORM\Entity(repositoryClass=FImage::class)
 * @ORM\Table(name="image")
 * Represents an image entity associated with a product.
 * @package EpTech\Entity
 */
class EImage {

    /**
     * @var int|null The unique identifier of the image.
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private int|null $idImage = null;

    /**
     * @var string The name of the image.
     * @ORM\Column(type="string", length=70, columnDefinition="VARCHAR(70)")
     */
    private $name;

    /**
     * @var int The size of the image in bytes.
     * @ORM\Column(type="integer", columnDefinition="INT(9)")
     */
    private $size;

    /**
     * @var string The MIME type of the image.
     * @ORM\Column(type="string", length=20, columnDefinition="VARCHAR(20)")
     */
    private $type;

    /**
     * @var resource|string The binary data of the image.
     * @ORM\Column(type="blob")
     */
    private $imageData;

    /**
     * @var EProduct|null The product associated with the image.
     * @ORM\ManyToOne(targetEntity=EProduct::class, inversedBy="images")
     * @ORM\JoinColumn(name="productId", referencedColumnName="productId", nullable=true)
     */
    private EProduct|null $product = null;

    /**
     * Constructor for the EImage class.
     * @param string $name The name of the image.
     * @param int $size The size of the image in bytes.
     * @param string $type The MIME type of the image.
     * @param resource|string $imageData The binary data of the image.
     */
    public function __construct($name, $size, $type, $imageData) {
        $this->name = $name;
        $this->size = $size;
        $this->type = $type;
        $this->imageData = $imageData;
    }

    /**
     * Returns the encoded binary data of the image.
     * @return string The binary data of the image.
     */
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
     * Returns the unique identifier of the image.
     * @return int|null The unique identifier of the image.
     */
    public function getIdImage() {
        return $this->idImage;
    }

    /**
     * Sets the unique identifier of the image.
     * @param int|null $idImage The unique identifier of the image.
     * @return void
     */
    public function setIdImage($idImage) {
        $this->idImage = $idImage;
    }

    /**
     * Returns the name of the image.
     * @return string The name of the image.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the name of the image.
     * @param string $name The name of the image.
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Returns the size of the image in bytes.
     * @return int The size of the image in bytes.
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Sets the size of the image in bytes.
     * @param int $size The size of the image in bytes.
     * @return void
     */
    public function setSize($size) {
        $this->size = $size;
    }

    /**
     * Returns the MIME type of the image.
     * @return string The MIME type of the image.
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Sets the MIME type of the image.
     * @param string $type The MIME type of the image.
     * @return void
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Returns the binary data of the image.
     * @return resource|string The binary data of the image.
     */
    public function getImageData() {
        return $this->imageData;
    }

    /**
     * Sets the binary data of the image.
     * @param resource|string $imageData The binary data of the image.
     * @return void
     */
    public function setImageData($imageData) {
        $this->imageData = $imageData;
    }

    /**
     * Returns the product associated with the image.
     * @return EProduct|null The associated product.
     */
    public function getProduct(): ?EProduct {
        return $this->product;
    }

    /**
     * Sets the product associated with the image.
     * @param EProduct|null $product The associated product.
     * @return void
     */
    public function setProduct(?EProduct $product) {
        $this->product = $product;
    }
}