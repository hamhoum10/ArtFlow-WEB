<?php

namespace App\Entity;

use App\Entity\Client;
use App\Entity\Enchere;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParticipantRepository;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $idp = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:"prix requis")]
    #[Assert\Type(type: 'numeric', message: "Le montant doit être un nombre.")]

    #[Groups("participant")]
    private ?float $montant =null;

    #[ORM\ManyToOne(targetEntity: Client::class )]
    #[ORM\JoinColumn(name: 'id', referencedColumnName: 'id')]
    #[Groups("participant")]
    private ?Client $id;

    #[ORM\ManyToOne(targetEntity: Enchere::class )]
    #[ORM\JoinColumn(name: 'ide', referencedColumnName: 'ide')]
    #[Groups("participant")]
    private ?Enchere $ide;

    public function getIdp(): ?int
    {
        return $this->idp;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }



    public function getId(): ?Client
    {
        return $this->id;
    }

    public function setId(?Client $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIde(): ?Enchere
    {
        return $this->ide;
    }

    public function setIde(?Enchere $ide): self
    {
        $this->ide = $ide;

        return $this;
    }


}