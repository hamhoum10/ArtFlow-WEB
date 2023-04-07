<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="commende_fk", columns={"id_commende"})})
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name_produit", type="string", length=255, nullable=false)
     */
    private $nameProduit;

    /**
     * @var string
     *
     * @ORM\Column(name="artiste", type="string", length=255, nullable=false)
     */
    private $artiste;

    /**
     * @var string
     *
     * @ORM\Column(name="addres", type="string", length=255, nullable=false)
     */
    private $addres;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sort", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateSort = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255, nullable=false)
     */
    private $userName;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commende", referencedColumnName="id")
     * })
     */
    private $idCommende;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameProduit(): ?string
    {
        return $this->nameProduit;
    }

    public function setNameProduit(string $nameProduit): self
    {
        $this->nameProduit = $nameProduit;

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

    public function getDateSort(): ?\DateTimeInterface
    {
        return $this->dateSort;
    }

    public function setDateSort(\DateTimeInterface $dateSort): self
    {
        $this->dateSort = $dateSort;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getIdCommende(): ?Commande
    {
        return $this->idCommende;
    }

    public function setIdCommende(?Commande $idCommende): self
    {
        $this->idCommende = $idCommende;

        return $this;
    }


}
