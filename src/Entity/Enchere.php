<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Enchere
 *
 * @ORM\Table(name="enchere")
 * @ORM\Entity
 */
class Enchere
{
    /**
     * @var int
     *
     * @ORM\Column(name="ide", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ide;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=200, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prixdepart", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixdepart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_limite", type="date", nullable=false)
     */
    private $dateLimite;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=250, nullable=false)
     */
    private $image;

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
