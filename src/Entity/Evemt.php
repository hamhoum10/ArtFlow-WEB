<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evemt
 *
 * @ORM\Table(name="evemt", indexes={@ORM\Index(name="id_art", columns={"id_art"})})
 * @ORM\Entity
 */
class Evemt
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_evemt", type="date", nullable=true)
     */
    private $dateEvemt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="finish_hour", type="string", length=200, nullable=false)
     */
    private $finishHour;

    /**
     * @var string
     *
     * @ORM\Column(name="start_hour", type="string", length=200, nullable=false)
     */
    private $startHour;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=200, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="capacity", type="string", length=200, nullable=false)
     */
    private $capacity;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=250, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var \Artiste
     *
     * @ORM\ManyToOne(targetEntity="Artiste")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_art", referencedColumnName="id_artiste")
     * })
     */
    private $idArt;


}
