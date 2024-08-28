<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $prixTotal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    /**
    * @var Collection<int, CommandeMateriel>
    */
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMateriel::class, cascade: ['persist', 'remove'])]
    private Collection $commandeMateriels;

    public function __construct()
    {
        $this->commandeMateriels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrixTotal(): ?int
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(int $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getCommandeMateriels(): Collection
{
    return $this->commandeMateriels;
}

public function addCommandeMateriel(CommandeMateriel $commandeMateriel): static
{
    if (!$this->commandeMateriels->contains($commandeMateriel)) {
        $this->commandeMateriels->add($commandeMateriel);
        $commandeMateriel->setCommande($this);
    }

    return $this;
}

public function removeCommandeMateriel(CommandeMateriel $commandeMateriel): static
{
    if ($this->commandeMateriels->removeElement($commandeMateriel)) {
        if ($commandeMateriel->getCommande() === $this) {
            $commandeMateriel->setCommande(null);
        }
    }

    return $this;
}
}
