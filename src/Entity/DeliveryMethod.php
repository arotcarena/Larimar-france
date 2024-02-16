<?php

namespace App\Entity;

use App\Config\TextConfig;
use App\Repository\DeliveryMethodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeliveryMethodRepository::class)]
class DeliveryMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $enName = null;

    #[ORM\Column]
    private ?int $minWeight = null;

    #[ORM\Column]
    private ?int $maxWeight = null;

    #[ORM\Column(length: 255)]
    private ?string $destinationArea = null;

    #[Assert\Positive(message: 'Le délai de livraison doit être un nombre entier positif')]
    #[ORM\Column(nullable: true)]
    private ?int $deliveryTime = null;

    #[Assert\NotBlank(message: 'Le prix est obligatoire')]
    #[Assert\NotNull(message: 'Le prix est obligatoire')]
    #[Assert\PositiveOrZero(message: 'Le prix ne peut pas être inférieur à zéro')]
    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinationAreaLabel(): string 
    {
        if($this->destinationArea === null)
        {
            return null;
        }
        return TextConfig::AREA_LABELS[$this->destinationArea];
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

    public function getEnName(): ?string
    {
        return $this->enName;
    }

    public function setEnName(string $enName): self
    {
        $this->enName = $enName;

        return $this;
    }

    public function getMinWeight(): ?int
    {
        return $this->minWeight;
    }

    public function setMinWeight(int $minWeight): self
    {
        $this->minWeight = $minWeight;

        return $this;
    }

    public function getMaxWeight(): ?int
    {
        return $this->maxWeight;
    }

    public function setMaxWeight(int $maxWeight): self
    {
        $this->maxWeight = $maxWeight;

        return $this;
    }

    public function getDestinationArea(): ?string
    {
        return $this->destinationArea;
    }

    public function setDestinationArea(string $destinationArea): self
    {
        $this->destinationArea = $destinationArea;

        return $this;
    }

    public function getDeliveryTime(): ?int
    {
        return $this->deliveryTime;
    }

    public function setDeliveryTime(?int $deliveryTime): self
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }
}
