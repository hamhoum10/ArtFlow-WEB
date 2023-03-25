<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;


/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="name_categorie", type="string", length=255, nullable=false)
     */
    #[ORM\Column(length:255)]
    private ?string $nameCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    #[ORM\Column(length:255)]
    private ?string $description;

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
