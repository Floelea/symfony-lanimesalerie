<?php

namespace App\Entity;

use App\Repository\ProductSubCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductSubCategoryRepository::class)]
class ProductSubCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'productSubCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private $productCategory;

    #[ORM\OneToMany(mappedBy: 'productSubCategory', targetEntity: Product::class, orphanRemoval: true)]
    private $product;

    #[ORM\ManyToOne(targetEntity: AnimalCategory::class, inversedBy: 'productSubCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private $animalCategory;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProductCategory(): ?productCategory
    {
        return $this->productCategory;
    }

    public function setProductCategory(?productCategory $productCategory): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setProductSubCategory($this);
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductSubCategory() === $this) {
                $product->setProductSubCategory(null);
            }
        }

        return $this;
    }

    public function getAnimalCategory(): ?animalCategory
    {
        return $this->animalCategory;
    }

    public function setAnimalCategory(?animalCategory $animalCategory): self
    {
        $this->animalCategory = $animalCategory;

        return $this;
    }
}
