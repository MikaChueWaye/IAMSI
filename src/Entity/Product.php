<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotNull]
    #[ORM\Column]
    private ?int $price = 0;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $ref = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageProduct = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $type = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getImageProduct(): ?string
    {
        return $this->imageProduct;
    }

    public function setImageProduct(?string $imageProduct): static
    {
        $this->imageProduct = $imageProduct;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

}
