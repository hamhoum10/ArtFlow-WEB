<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use DateTime;
use App\Repository\EnchereRepository;

#[ORM\Entity(repositoryClass: EnchereRepository::class)]
class Enchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ide = null;


    #[ORM\Column(length: 200)]
    private ?string $titre = null;

    #[ORM\Column(length: 200)]
    private ?string $description = null;


    #[ORM\Column]
    private ?float $prixdepart =null;



    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLimite = null;




    #[ORM\Column(length: 250)]
    private ?string $image= null;

    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixdepart(): ?float
    {
        return $this->prixdepart;
    }

    public function setPrixdepart(float $prixdepart): self
    {
        $this->prixdepart = $prixdepart;

        return $this;
    }


    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTimeInterface $dateLimite): self
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }




}
