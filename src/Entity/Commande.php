<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("commande")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("commande")]
    private ?int $id_panier = null;

    #[ORM\Column(length: 255)]
    #[Groups("commande")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups("commande")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups("commande")]
    private ?string $numero = null;

    #[ORM\Column(length: 255)]
    #[Groups("commande")]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups("commande")]
    private ?int $total_amount = null;

    #[ORM\Column]
    #[Groups("commande")]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups("commande")]
    private ?int $codepostal = null;

    #[ORM\Column(length: 255)]
    #[Groups("commande")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups("commande")]
    private ?string $Statu_liv = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPanier(): ?int
    {
        return $this->id_panier;
    }

    public function setIdPanier(int $id_panier): self
    {
        $this->id_panier = $id_panier;

        return $this;
    }


    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): self
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCodepostal(): ?int
    {
        return $this->codepostal;
    }

    public function setCodepostal(int $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function __toString(): string
    {
       return $this->getNom();
    }

    public function getStatuLiv(): ?string
    {
        return $this->Statu_liv;
    }

    public function setStatuLiv(?string $Statu_liv): self
    {
        $this->Statu_liv = $Statu_liv;

        return $this;
    }

}
