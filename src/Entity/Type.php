<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Dwelling::class)]
    private Collection $dwellings;

    public function __construct()
    {
        $this->dwellings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Dwelling>
     */
    public function getDwellings(): Collection
    {
        return $this->dwellings;
    }

    public function addDwelling(Dwelling $dwelling): self
    {
        if (!$this->dwellings->contains($dwelling)) {
            $this->dwellings->add($dwelling);
            $dwelling->setType($this);
        }

        return $this;
    }

    public function removeDwelling(Dwelling $dwelling): self
    {
        if ($this->dwellings->removeElement($dwelling)) {
            // set the owning side to null (unless already changed)
            if ($dwelling->getType() === $this) {
                $dwelling->setType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
