<?php

namespace App\Entity;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(name: 'date_send', type: 'datetime', nullable: true)]
    private $dateSend;

    #[ORM\ManyToMany(targetEntity: "User")]
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }
        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);
        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getDateSend(): \DateTimeInterface
    {
        return $this->dateSend;
    }

    public function setDateSend(DateTimeInterface $date): self
    {
        $this->dateSend = $date;

        return $this;
    }
}