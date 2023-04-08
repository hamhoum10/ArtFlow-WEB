<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\CategorieRepository;



#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $idCategorie;


    #[ORM\Column(length:255)]
    private ?string $nameCategorie = null;


    #[ORM\Column(length:255)]
    private ?string $description = null;

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
    }

    public function getNameCategorie(): ?string
    {
        return $this->nameCategorie;
    }

    public function setNameCategorie(string $nameCategorie): self
    {
        $this->nameCategorie = $nameCategorie;

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


}
