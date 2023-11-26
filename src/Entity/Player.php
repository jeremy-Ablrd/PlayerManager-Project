<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Contracts::class)]
    private Collection $contracts;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Statistics $statistics = null;

    #[ORM\ManyToMany(targetEntity: Clubs::class, inversedBy: 'players')]
    private Collection $clubsName;

    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->clubsName = new ArrayCollection();
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

    public function __toString()
    {
        return $this->name;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Contracts>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contracts $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setPlayer($this);
        }

        return $this;
    }

    public function removeContract(Contracts $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getPlayer() === $this) {
                $contract->setPlayer(null);
            }
        }

        return $this;
    }

    public function getStatistics(): ?Statistics
    {
        return $this->statistics;
    }

    public function setStatistics(?Statistics $statistics): static
    {
        $this->statistics = $statistics;

        return $this;
    }

    /**
     * @return Collection<int, Clubs>
     */
    public function getClubsName(): Collection
    {
        return $this->clubsName;
    }

    public function addClubsName(Clubs $clubsName): static
    {
        if (!$this->clubsName->contains($clubsName)) {
            $this->clubsName->add($clubsName);
        }

        return $this;
    }

    public function removeClubsName(Clubs $clubsName): static
    {
        $this->clubsName->removeElement($clubsName);

        return $this;
    }
}
