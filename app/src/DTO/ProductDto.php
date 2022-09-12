<?php

namespace App\DTO;

class ProductDto
{
    private $article;
    private $name;
    private $dateCreate;
    private $dateUpdate;
    private $price;
    private $count;

    public function __construct(int $article, string $name, string $dateCreate, string $dateUpdate, int $price, int $count)
    {
        $this->article = $article;
        $this->name = $name;
        $this->dateCreate = $dateCreate;
        $this->dateUpdate = $dateUpdate;
        $this->price = $price;
        $this->count = $count;
    }

    public static function fromArray(array $product)
    {
        return new self
        (
            $product['article'],
            $product['name'],
            $product['date_create'],
            $product['date_update'],
            $product['price'],
            $product['count']
        );
    }

    /**
     * @return int
     */
    public function getArticle(): int
    {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDateCreate(): \DateTimeInterface
    {
        return new \DateTime($this->dateCreate);
    }

    /**
     * @return string
     */
    public function getDateUpdate(): \DateTimeInterface
    {
        return new \DateTime($this->dateUpdate);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }
}