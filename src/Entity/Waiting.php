<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Waiting
 *
 * @ORM\Table(name="waiting", indexes={@ORM\Index(name="user_name", columns={"user_name"}), @ORM\Index(name="id_ligne_panier", columns={"id_ligne_panier"}), @ORM\Index(name="id_commande", columns={"id_commande"})})
 * @ORM\Entity
 */
class Waiting
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_waiting", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idWaiting;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_produit", type="string", length=255, nullable=false)
     */
    private $nomProduit;

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
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id")
     * })
     */
    private $idCommande;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_name", referencedColumnName="id_client")
     * })
     */
    private $userName;

    /**
     * @var \LignePanier
     *
     * @ORM\ManyToOne(targetEntity="LignePanier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ligne_panier", referencedColumnName="id")
     * })
     */
    private $idLignePanier;

    public function getIdWaiting(): ?int
    {
        return $this->idWaiting;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

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

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getUserName(): ?Panier
    {
        return $this->userName;
    }

    public function setUserName(?Panier $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getIdLignePanier(): ?LignePanier
    {
        return $this->idLignePanier;
    }

    public function setIdLignePanier(?LignePanier $idLignePanier): self
    {
        $this->idLignePanier = $idLignePanier;

        return $this;
    }


}
