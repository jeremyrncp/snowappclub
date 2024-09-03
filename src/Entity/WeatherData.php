<?php

namespace App\Entity;

use App\Repository\WeatherDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: WeatherDataRepository::class)]
class WeatherData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['weatherdata:public', 'weatherdata:apicsv'])]
    private ?float $tint = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['weatherdata:public', 'weatherdata:apicsv'])]
    private ?float $tout = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['weatherdata:public', 'weatherdata:apicsv'])]
    private ?float $rhint = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['weatherdata:public', 'weatherdata:apicsv'])]
    private ?float $rhout = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['weatherdata:public', 'weatherdata:apicsv'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'weatherData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WeatherStation $station = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTint(): ?float
    {
        return $this->tint;
    }

    public function setTint(?float $tint): static
    {
        $this->tint = $tint;

        return $this;
    }

    public function getTout(): ?float
    {
        return $this->tout;
    }

    public function setTout(?float $tout): static
    {
        $this->tout = $tout;

        return $this;
    }

    public function getRhint(): ?float
    {
        return $this->rhint;
    }

    public function setRhint(?float $rhint): static
    {
        $this->rhint = $rhint;

        return $this;
    }

    public function getRhout(): ?float
    {
        return $this->rhout;
    }

    public function setRhout(?float $rhout): static
    {
        $this->rhout = $rhout;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStation(): ?WeatherStation
    {
        return $this->station;
    }

    public function setStation(?WeatherStation $station): static
    {
        $this->station = $station;

        return $this;
    }
}
