<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="id_event", columns={"id_event"}), @ORM\Index(name="id_client", columns={"id_client"})})
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_res", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRes;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_place", type="integer", nullable=false)
     */
    private $nbPlace;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateres", type="date", nullable=true)
     */
    private $dateres;

    /**
     * @var \Evemt
     *
     * @ORM\ManyToOne(targetEntity="Evemt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_event", referencedColumnName="id")
     * })
     */
    private $idEvent;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     * })
     */
    private $idClient;


}
