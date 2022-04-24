<?php

namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'productCategory', targetEntity: Product::class, orphanRemoval: true)]
    private $product;

    #[ORM\OneToMany(mappedBy: 'productCategory', targetEntity: ProductSubCategory::class)]
    private $productSubCategories;

    #[ORM\ManyToOne(targetEntity: AnimalCategory::class, inversedBy: 'productCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private $animalCategory;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->productSubCategories = new ArrayCollection();
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
            $product->setProductCategory($this);
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductCategory() === $this) {
                $product->setProductCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductSubCategory>
     */
    public function getProductSubCategories(): Collection
    {
        return $this->productSubCategories;
    }

    public function addProductSubCategory(ProductSubCategory $productSubCategory): self
    {
        if (!$this->productSubCategories->contains($productSubCategory)) {
            $this->productSubCategories[] = $productSubCategory;
            $productSubCategory->setProductCategory($this);
        }

        return $this;
    }

    public function removeProductSubCategory(ProductSubCategory $productSubCategory): self
    {
        if ($this->productSubCategories->removeElement($productSubCategory)) {
            // set the owning side to null (unless already changed)
            if ($productSubCategory->getProductCategory() === $this) {
                $productSubCategory->setProductCategory(null);
            }
        }

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
