<?php

namespace App\Entity;

use App\Repository\PriceHistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceHistRepository::class)]
class PriceHist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'integer')]
    private $delta;

    #[ORM\Column(type: 'integer')]
    private $currentPrice;

    #[ORM\ManyToOne(targetEntity: "Products", inversedBy: "priceHist")]
    #[ORM\JoinColumn(name:"product", onDelete: 'CASCADE')]
    private $product;



    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Products
    {
        return $this->product;
    }

    public function setProduct(Products $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDelta(): int
    {
        return $this->delta;
    }

    public function setDelta(int $delta): self
    {
        $this->delta = $delta;

        return $this;
    }

    public function getCurrentPrice(): int
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(int $currentPrice): self
    {
        $this->currentPrice = $currentPrice;
        return  $this;
    }
}
