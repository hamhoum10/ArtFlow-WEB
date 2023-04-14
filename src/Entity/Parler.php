<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

//    public function __construct()
//    {
//        $this->idEvemt = new ArrayCollection();
//        $this->idClient = new ArrayCollection();
//    }

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

//    public function addIdEvemt(Evemt $idEvemt): self
//    {
//        if (!$this->idEvemt->contains($idEvemt)) {
//            $this->idEvemt->add($idEvemt);
//        }
//
//        return $this;
//    }
//
//    public function removeIdEvemt(Evemt $idEvemt): self
//    {
//        $this->idEvemt->removeElement($idEvemt);
//
//        return $this;
//    }
//
//    public function addIdClient(Client $idClient): self
//    {
//        if (!$this->idClient->contains($idClient)) {
//            $this->idClient->add($idClient);
//        }
//
//        return $this;
//    }
//
//    public function removeIdClient(Client $idClient): self
//    {
//        $this->idClient->removeElement($idClient);
//
//        return $this;
//    }


}
