<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvemtRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvemtRepository::class)]
class Evemt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;



    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column (type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateEvemt = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String $finishHour = null;


    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String  $startHour = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String $location =  null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String $capacity = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String $image = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String $name = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column]
    private ?float $prix = null;

    #[Assert\NotBlank(message:"Veuillez saisir tout les champs")]
    #[ORM\Column(length: 255)]
    private ?String $username = null;

    #[ORM\ManyToOne(targetEntity: Artiste::class)]
    #[ORM\JoinColumn(name: 'id_artiste', referencedColumnName: 'id_artiste')]
    private ?Artiste $idArtiste;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEvemt(): ?\DateTimeInterface
    {
        return $this->dateEvemt;
    }

    public function setDateEvemt(?\DateTimeInterface $dateEvemt): self
    {
        $this->dateEvemt = $dateEvemt;

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

    public function getFinishHour(): ?string
    {
        return $this->finishHour;
    }

    public function setFinishHour(string $finishHour): self
    {
        $this->finishHour = $finishHour;

        return $this;
    }

    public function getStartHour(): ?string
    {
        return $this->startHour;
    }

    public function setStartHour(string $startHour): self
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCapacity(): ?string
    {
        return $this->capacity;
    }

    public function setCapacity(string $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }
    public function getImage(): ?string
    {
        $image = $this->image;
        if (!$image) {
            return null;
        }
        return 'http://localhost/img/' . $image;
    }

//    public function setImage(string $image): self
//    {
//        $this->image = $image;
//
//        return $this;
//    }

    public function setImage(UploadedFile $image): self
    {
        $extension = $image->getClientOriginalExtension();
        $newFileName = uniqid().'.'.$extension;
        $image->move('C:\xampp\htdocs\img', $newFileName);
        $this->image = $newFileName;

        return $this;
    }

//    public function getImage(): ?string
//    {
//        return $this->image;
//    }
//
//    public function setImage(string $image): self
//    {
//        $this->image = $image;
//
//        return $this;
//    }




    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getIdArtiste(): ?Artiste
    {
        return $this->idArtiste;
    }

    public function setIdArtiste(?Artiste $idArtiste): self
    {
        $this->idArtiste = $idArtiste;

        return $this;
    }


}
