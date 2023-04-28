<?php

namespace App\Entity;

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


}
