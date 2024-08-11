<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LocalisationRepository::class)]
class Localisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['localisation:public', 'snowdata:public'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['localisation:public', 'snowdata:public'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['localisation:public', 'snowdata:public'])]
    private ?int $elevation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['localisation:public'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['localisation:public'])]
    private ?string $country = null;

    #[ORM\ManyToOne(inversedBy: 'localisations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, SnowData>
     */
    #[ORM\OneToMany(targetEntity: SnowData::class, mappedBy: 'localisation')]
    private Collection $snowData;

    public function __construct()
    {
        $this->snowData = new ArrayCollection();
    }

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

    public function getElevation(): ?int
    {
        return $this->elevation;
    }

    public function setElevation(int $elevation): static
    {
        $this->elevation = $elevation;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, SnowData>
     */
    public function getSnowData(): Collection
    {
        return $this->snowData;
    }

    public function addSnowData(SnowData $snowData): static
    {
        if (!$this->snowData->contains($snowData)) {
            $this->snowData->add($snowData);
            $snowData->setLocalisation($this);
        }

        return $this;
    }

    public function removeSnowData(SnowData $snowData): static
    {
        if ($this->snowData->removeElement($snowData)) {
            // set the owning side to null (unless already changed)
            if ($snowData->getLocalisation() === $this) {
                $snowData->setLocalisation(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name . " (" . $this->city . ", " . $this->country.")";
    }
}
