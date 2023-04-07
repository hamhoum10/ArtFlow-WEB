<?php

namespace App\Entity;

use App\Repository\RetourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RetourRepository::class)]
class Retour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $name_produit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $artiste = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $addres = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToOne()]
    private ?Commande $id_commende = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $user_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNameProduit(): ?string
    {
        return $this->name_produit;
    }

    public function setNameProduit(string $name_produit): self
    {
        $this->name_produit = $name_produit;

        return $this;
    }

    public function getArtiste(): ?string
    {
        return $this->artiste;
    }

    public function setArtiste(string $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getAddres(): ?string
    {
        return $this->addres;
    }

    public function setAddres(string $addres): self
    {
        $this->addres = $addres;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdCommende(): ?commande
    {
        return $this->id_commende;
    }

    public function setIdCommende(?commande $id_commende): self
    {
        $this->id_commende = $id_commende;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }
}
