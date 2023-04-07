<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ParlerRepository;

#[ORM\Entity(repositoryClass: ParlerRepository::class)]

/**
 * Parler
 *
 * @ORM\Table(name="parler", indexes={@ORM\Index(name="id_evemt", columns={"id_evemt"}), @ORM\Index(name="id_client", columns={"id_client"})})
 * @ORM\Entity
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\ParlerRepository")
 */
class Parler
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_comment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComment;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=false)
     */
    private $commentaire;

    /**
     * @var \Evemt
     *
     * @ORM\ManyToOne(targetEntity="Evemt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evemt", referencedColumnName="id")
     * })
     */
    private $idEvemt;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     * })
     */
    private $idClient;

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

    public function getIdEvemt(): ?Evemt
    {
        return $this->idEvemt;
    }

    public function setIdEvemt(?Evemt $idEvemt): self
    {
        $this->idEvemt = $idEvemt;

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
