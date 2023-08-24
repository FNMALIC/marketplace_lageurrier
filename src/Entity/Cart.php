<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $userCart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserCart(): ?User
    {
        return $this->userCart;
    }

    public function setUserCart(?User $userCart): static
    {
        $this->userCart = $userCart;

        return $this;
    }
}
