<?php

namespace App\Entity;

use App\Repository\OrderStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderStatusRepository::class)]
class OrderStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'orderStatus', targetEntity: Order::class)]
    private $orderObject;

    public function __construct()
    {
        $this->orderObject = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrderObject(): Collection
    {
        return $this->orderObject;
    }

    public function addOrderObject(Order $orderObject): self
    {
        if (!$this->orderObject->contains($orderObject)) {
            $this->orderObject[] = $orderObject;
            $orderObject->setOrderStatus($this);
        }

        return $this;
    }

    public function removeOrderObject(Order $orderObject): self
    {
        if ($this->orderObject->removeElement($orderObject)) {
            // set the owning side to null (unless already changed)
            if ($orderObject->getOrderStatus() === $this) {
                $orderObject->setOrderStatus(null);
            }
        }

        return $this;
    }
}
