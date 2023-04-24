<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParlerRepository;

#[ORM\Entity(repositoryClass: ParlerRepository::class)]

class Parler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idComment =null;

    #[ORM\Column(length: 255)]
    private ?string $commentaire =null;

    #[ORM\ManyToOne(targetEntity: Evemt::class)]
    #[ORM\JoinColumn(name: 'id_evemt', referencedColumnName: 'id')]
    private ?Evemt  $idEvemt;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id')]
    private ?Client $idClient;


    public function getIdComment(): ?int
    {
        return $this->idComment;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

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

    public function getIdEvemt(): ?Evemt
    {
        return $this->idEvemt;
    }

    public function setIdEvemt(?Evemt $idEvemt): self
    {
        $this->idEvemt = $idEvemt;

        return $this;
    }


}
