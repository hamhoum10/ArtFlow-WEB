<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use DateTime;
use App\Repository\EnchereRepository;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EnchereRepository::class)]
class Enchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $ide = null;


    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message:"titre requis")]
    private ?string $titre = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message:"description requise")]
    private ?string $description = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:"prix requis")]
    #[Assert\Type(type: 'numeric', message: "Le prix doit être un nombre.")]
    private ?float $prixdepart =null;



    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'La date limite est requise.')]
    #[Assert\GreaterThanOrEqual(
        value: 'today',
        message: 'La date limite doit être supérieure ou égale à la date actuelle.'
    )]
    private ?\DateTimeInterface $dateLimite = null;




    #[ORM\Column(length: 250)]
    #[Assert\NotBlank(message:"nom requis")]
    private ?string $image= null;



    /**
     * @ ORM\OneToMany(targetEntity=Participant::class, mappedBy="enchere", orphanRemoval=true)

    private $participant;

    // ...

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setParticipant(?Participant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
     */
    /**
     * @ORM\Column(type="array")
     */
    private $images = [];

    // getters and setters for all properties

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }
    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixdepart(): ?float
    {
        return $this->prixdepart;
    }

    public function setPrixdepart(float $prixdepart): self
    {
        $this->prixdepart = $prixdepart;

        return $this;
    }


    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTimeInterface $dateLimite): self
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }




}
