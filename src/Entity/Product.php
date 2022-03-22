<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
/**
 * @Vich\Uploadable
 */
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $priceHt;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;


    #[ORM\Column(type: 'text')]
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceHt(): ?float
    {
        return $this->priceHt;
    }

    public function setPriceHt(float $priceHt): self
    {
        $this->priceHt = $priceHt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     *@Vich\UploadableField(mapping= "products", fileNameProperty= "imageName", size= "imageSize")
     */
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string',nullable:true)]
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $imageName = null;

    #[ORM\Column(type: 'integer',nullable:true)]
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $imageSize = null;

    #[ORM\Column(type: 'datetime',nullable:true)]
    /**
     * @ORM\Column(type="dateTime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: productCategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\ManyToOne(targetEntity: ProductSubCategory::class, inversedBy: 'product')]
    #[ORM\JoinColumn(nullable: false)]
    private $productSubCategory;

    #[ORM\ManyToOne(targetEntity: AnimalCategory::class, inversedBy: 'product')]
    #[ORM\JoinColumn(nullable: false)]
    private $animalCategory;


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?productCategory
    {
        return $this->category;
    }

    public function setCategory(?productCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getProductSubCategory(): ?ProductSubCategory
    {
        return $this->productSubCategory;
    }

    public function setProductSubCategory(?ProductSubCategory $productSubCategory): self
    {
        $this->productSubCategory = $productSubCategory;

        return $this;
    }

    public function getAnimalCategory(): ?AnimalCategory
    {
        return $this->animalCategory;
    }

    public function setAnimalCategory(?AnimalCategory $animalCategory): self
    {
        $this->animalCategory = $animalCategory;

        return $this;
    }

}
