<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="id_event", columns={"id_event"}), @ORM\Index(name="id_client", columns={"id_client"})})
 * @ORM\Entity
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
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
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     * })
     */
    private $idClient;

    /**
     * @var \Evemt
     *
     * @ORM\ManyToOne(targetEntity="Evemt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_event", referencedColumnName="id")
     * })
     */
    private $idEvent;

    public function getIdRes(): ?int
    {
        return $this->idRes;
    }

    public function getNbPlace(): ?int
    {
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

        return $this;
    }

    public function getDateres(): ?\DateTimeInterface
    {
        return $this->dateres;
    }

    public function setDateres(?\DateTimeInterface $dateres): self
    {
        $this->dateres = $dateres;

        return $this;
    }

    public function getIdClient(): ?Client
    {
        return $this->idClient;
    }

    public function setIdClient(?Client $idClient): self
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getIdEvent(): ?Evemt
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evemt $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }
    public function __toString()
    {
        return (string) $this->id_client;
    }


}
