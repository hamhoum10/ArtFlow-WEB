<?php

namespace App\Entity;

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


}
