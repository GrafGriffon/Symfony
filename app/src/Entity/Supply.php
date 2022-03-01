<?php

namespace App\Entity;

use App\Repository\SupplyRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplyRepository::class)]
class Supply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $supplier;

    #[ORM\Column(type: 'integer')]
    private $period_of_execution;

    #[ORM\ManyToMany(targetEntity: "Products", inversedBy: "supplyer")]
    private $products;

    #[ORM\OneToMany(targetEntity: "User", mappedBy: "supply")]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return Collection|Products[]
     */
    public function getProduct(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addSupply($this);
        }
        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeSupply($this);
        }
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplier(): ?string
    {
        return $this->supplier;
    }

    public function setSupplier(string $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getPeriodOfExecution(): ?int
    {
        return $this->period_of_execution;
    }

    public function setPeriodOfExecution(int $period_of_execution): self
    {
        $this->period_of_execution = $period_of_execution;
        return $this;
    }
}
