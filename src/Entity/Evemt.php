<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvemtRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
date_default_timezone_set('Africa/Tunis');

#[ORM\Entity(repositoryClass: EvemtRepository::class)]

/**
 * Evemt
 *
 * @ORM\Table(name="evemt", uniqueConstraints={@ORM\UniqueConstraint(name="id_artiste", columns={"id_artiste"})})
 * @ORM\Entity
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\EvemtRepository")
 */
class Evemt
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**

     *
     * @ORM\Column(name="date_evemt", type: Types::DATETIME_MUTABLE, nullable=true)
     */
    private $dateEvemt;
//    private ?\DateTimeInterface $dateEvemt = null;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="finish_hour", type="string", length=200, nullable=false)
     */
    private $finishHour;

    /**
     * @var string
     *
     * @ORM\Column(name="start_hour", type="string", length=200, nullable=false)
     */
    private $startHour;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=200, nullable=false)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="capacity", type="string", length=200, nullable=false)
     */
    private $capacity;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=250, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=200, nullable=false)
     */
    private $username;

    /**
     * @var \Artiste
     *
     * @ORM\ManyToOne(targetEntity="Artiste")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_artiste", referencedColumnName="id_artiste")
     * })
     */
//    public function __construct()
//    {
////        $this->comments = new ArrayCollection();
////        $this->postLikes = new ArrayCollection();
//
//        $this->dateEvemt = new \DateTime();
//    }


    private $idArtiste;

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
//    public function setDateEvemt(\DateTimeInterface $dateEvemt = null): self
//    {
//
//        if (!$dateEvemt) {
//            $dateEvemt = new \DateTime('now', new \DateTimeZone('Africa/Tunis'));
//        }
//        $this->dateEvemt = $dateEvemt;
//        return $this;
//    }





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

//    public function getImage(): ?string
//    {
//        return $this->image;
//    }
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
    public function __toString()
    {
        return (string) $this->idArtiste;
    }


}
