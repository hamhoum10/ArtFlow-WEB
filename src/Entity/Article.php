<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use App\Repository\ArticteRepository;
use Doctrine\ORM\Mapping as ORM;
Use App\Entity\Artiste;
Use App\Entity\Categorie;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $idArticle= null;


    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $nomArticle;


    #[ORM\Column]
    #[Assert\NotBlank(message:"nom requis")]
    private ?float $price;


    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $type;


    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $image;


    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $description;


    #[ORM\Column]
    #[Assert\NotBlank(message:"nom requis")]
    private ?int $quantity;


    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\Column]
    #[Assert\NotBlank(message:"nom requis")]

    private ?string $idArtiste;


    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\Column]
    #[Assert\NotBlank(message:"nom requis")]
    private ?int $idCategorie;

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getNomArticle(): ?string
    {
        return $this->nomArticle;
    }

    public function setNomArticle(string $nomArticle): self
    {
        $this->nomArticle = $nomArticle;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getIdArtiste()
    {
        return $this->idArtiste;
    }

    public function setIdArtiste(?string $idArtiste): self
    {
        $this->idArtiste = $idArtiste;

        return $this;
    }

    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?int $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }


}
