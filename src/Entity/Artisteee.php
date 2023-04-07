<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Artisteee
 *
 * @ORM\Table(name="artisteee")
 * @ORM\Entity
 */
class Artisteee
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_artiste", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArtiste;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_artiste", type="string", length=255, nullable=false)
     */
    private $nomArtiste;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_artiste", type="string", length=255, nullable=false)
     */
    private $prenomArtiste;

    public function getIdArtiste(): ?int
    {
        return $this->idArtiste;
    }

    public function getNomArtiste(): ?string
    {
        return $this->nomArtiste;
    }

    public function setNomArtiste(string $nomArtiste): self
    {
        $this->nomArtiste = $nomArtiste;

        return $this;
    }

    public function getPrenomArtiste(): ?string
    {
        return $this->prenomArtiste;
    }

    public function setPrenomArtiste(string $prenomArtiste): self
    {
        $this->prenomArtiste = $prenomArtiste;

        return $this;
    }


}
