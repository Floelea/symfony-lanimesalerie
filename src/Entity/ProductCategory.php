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

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class, orphanRemoval: true)]
    private $products;

    #[ORM\OneToMany(mappedBy: 'productCategory', targetEntity: productSubCategory::class, orphanRemoval: true)]
    private $productSubCategory;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productSubCategory = new ArrayCollection();
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
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, productSubCategory>
     */
    public function getProductSubCategory(): Collection
    {
        return $this->productSubCategory;
    }

    public function addProductSubCategory(productSubCategory $productSubCategory): self
    {
        if (!$this->productSubCategory->contains($productSubCategory)) {
            $this->productSubCategory[] = $productSubCategory;
            $productSubCategory->setProductCategory($this);
        }

        return $this;
    }

    public function removeProductSubCategory(productSubCategory $productSubCategory): self
    {
        if ($this->productSubCategory->removeElement($productSubCategory)) {
            // set the owning side to null (unless already changed)
            if ($productSubCategory->getProductCategory() === $this) {
                $productSubCategory->setProductCategory(null);
            }
        }

        return $this;
    }
}
