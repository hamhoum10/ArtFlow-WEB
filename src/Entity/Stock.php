<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table(name="stock", indexes={@ORM\Index(name="commendeee_fk", columns={"id_commende"})})
 * @ORM\Entity
 */
class Stock
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
     * @var int
     *
     * @ORM\Column(name="id_commende", type="integer", nullable=false)
     */
    private $idCommende;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255, nullable=false)
     */
    private $userName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_entr", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateEntr = 'CURRENT_TIMESTAMP';

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

    public function getIdCommende(): ?int
    {
        return $this->idCommende;
    }

    public function setIdCommende(int $idCommende): self
    {
        $this->idCommende = $idCommende;

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

    public function getDateEntr(): ?\DateTimeInterface
    {
        return $this->dateEntr;
    }

    public function setDateEntr(\DateTimeInterface $dateEntr): self
    {
        $this->dateEntr = $dateEntr;

        return $this;
    }


}
