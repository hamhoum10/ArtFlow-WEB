<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\CommandeRepository;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id =null;


    #[ORM\Column(length: 150)]
    private ?string $prenom =null;


    #[ORM\Column(length: 150)]
    private ?string $nom=null;


    #[ORM\Column]
    private ?int $numero=null;


    #[ORM\Column(length: 150)]
    private ?string $status=null;

    #[ORM\Column]
    private ?float $totalAmount=null;

    #[ORM\Column]
    private ?string $createdAt = null; //radithha null mesh te5dem


    #[ORM\Column]
    private ?int $codepostal=null;


    #[ORM\Column(length: 150)]
    private ?string $adresse=null;


    #[ORM\ManyToOne(targetEntity: Panier::class)]
    #[ORM\JoinColumn(name: 'id_panier', referencedColumnName: 'id_panier')]
    private ?Panier $idPanier =null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt ): self
    {
        $this->createdAt = $createdAt->format('Y-m-d H:i:s');

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

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }


}
