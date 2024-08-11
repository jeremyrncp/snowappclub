<?php

namespace App\Entity;

use App\Repository\SnowDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SnowDataRepository::class)]
class SnowData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['snowdata:public'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'snowData')]
    #[Groups(['snowdata:public'])]
    private ?Localisation $localisation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['snowdata:public'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['snowdata:public'])]
    private ?int $snowFresh = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['snowdata:public'])]
    private ?int $snowTotal = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['snowdata:public'])]
    private ?bool $snow = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSnowFresh(): ?int
    {
        return $this->snowFresh;
    }

    public function setSnowFresh(?int $snowFresh): static
    {
        $this->snowFresh = $snowFresh;

        return $this;
    }

    public function getSnowTotal(): ?int
    {
        return $this->snowTotal;
    }

    public function setSnowTotal(?int $snowTotal): static
    {
        $this->snowTotal = $snowTotal;

        return $this;
    }

    public function isSnow(): ?bool
    {
        return $this->snow;
    }

    public function setSnow(?bool $snow): static
    {
        $this->snow = $snow;

        return $this;
    }
}
