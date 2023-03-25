<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LignePanier
 *
 * @ORM\Table(name="ligne_panier", indexes={@ORM\Index(name="id_articleFK", columns={"id_article"}), @ORM\Index(name="id_panierfkoo", columns={"id_panier"})})
 * @ORM\Entity
 */
class LignePanier
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
     * @var float
     *
     * @ORM\Column(name="prix_unitaire", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixUnitaire;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_article", type="string", length=255, nullable=false)
     */
    private $nomArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_artiste", type="string", length=255, nullable=false)
     */
    private $nomArtiste;

    /**
     * @var string
     *
     * @ORM\Column(name="Prenom_artiste", type="string", length=255, nullable=false)
     */
    private $prenomArtiste;

    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_article", referencedColumnName="id_article")
     * })
     */
    private $idArticle;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id_panier")
     * })
     */
    private $idPanier;


}
