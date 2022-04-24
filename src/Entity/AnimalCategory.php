<?php

namespace App\Entity;

use App\Repository\AnimalCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalCategoryRepository::class)]
class AnimalCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'animalCategory', targetEntity: Product::class, orphanRemoval: true)]
    private $products;

    #[ORM\OneToMany(mappedBy: 'animalCategory', targetEntity: ProductCategory::class)]
    private $productCategories;

    #[ORM\OneToMany(mappedBy: 'animalCategory', targetEntity: ProductSubCategory::class, orphanRemoval: true)]
    private $productSubCategories;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productCategories = new ArrayCollection();
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
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setAnimalCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getAnimalCategory() === $this) {
                $product->setAnimalCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductCategory>
     */
    public function getProductCategories(): Collection
    {
        return $this->productCategories;
    }

    public function addProductCategory(ProductCategory $productCategory): self
    {
        if (!$this->productCategories->contains($productCategory)) {
            $this->productCategories[] = $productCategory;
            $productCategory->setAnimalCategory($this);
        }

        return $this;
    }

    public function removeProductCategory(ProductCategory $productCategory): self
    {
        if ($this->productCategories->removeElement($productCategory)) {
            // set the owning side to null (unless already changed)
            if ($productCategory->getAnimalCategory() === $this) {
                $productCategory->setAnimalCategory(null);
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
            $productSubCategory->setAnimalCategory($this);
        }

        return $this;
    }

    public function removeProductSubCategory(ProductSubCategory $productSubCategory): self
    {
        if ($this->productSubCategories->removeElement($productSubCategory)) {
            // set the owning side to null (unless already changed)
            if ($productSubCategory->getAnimalCategory() === $this) {
                $productSubCategory->setAnimalCategory(null);
            }
        }

        return $this;
    }
}
