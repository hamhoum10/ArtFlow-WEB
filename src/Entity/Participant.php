<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participant
 *
 * @ORM\Table(name="participant", indexes={@ORM\Index(name="fkclientttt", columns={"id"}), @ORM\Index(name="fkencheeee", columns={"ide"})})
 * @ORM\Entity
 */
class Participant
{
    /**
     * @var int
     *
     * @ORM\Column(name="idp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idp;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $montant;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * @var \Enchere
     *
     * @ORM\ManyToOne(targetEntity="Enchere")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ide", referencedColumnName="ide")
     * })
     */
    private $ide;

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
