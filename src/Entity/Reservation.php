<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
#[ORM\Entity(repositoryClass: ReservationRepository::class)]

class Reservation
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRes = null;



    #[ORM\Column]

    private ?float $nbPlace = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]

    private ?\DateTimeInterface $dateres =null;



    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id')]
    private ?Client $idClient ;

    #[ORM\ManyToOne(targetEntity: Evemt::class)]
    #[ORM\JoinColumn(name: 'id_event', referencedColumnName: 'id')]
    private ?Evemt  $idEvent;
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

    public function getIdEvent(): ?Evemt
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Evemt $idEvent): self
    {
        $this->idEvent = $idEvent;

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


}
