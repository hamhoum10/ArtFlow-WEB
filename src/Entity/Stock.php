<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("stock")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Product Name required")]
    #[Groups("stock")]
    private ?string $name_produit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Artiste required")]
    #[Groups("stock")]
    private ?string $artiste = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"addres required")]
    #[Groups("stock")]
    private ?string $addres = null;

    #[ORM\OneToOne()]
    #[Assert\NotNull(message:"Please select one")]
    private ?Commande $id_commende = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"User Name required")]
    #[Groups("stock")]
    private ?string $user_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\LessThanOrEqual('today',message:"We Do NOT LIVE IN THE FUTURE")]
    #[Groups("stock")]
    private ?\DateTimeInterface $date_entr = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNameProduit(): ?string
    {
        return $this->name_produit;
    }

    public function setNameProduit(string $name_produit): self
    {
        $this->name_produit = $name_produit;

        return $this;
    }

    public function getArtiste(): ?string
    {
        return $this->artiste;
    }

    public function setArtiste(string $artiste): self
    {
        $this->artiste = $artiste;

        return $this;
    }

    public function getAddres(): ?string
    {
        return $this->addres;
    }

    public function setAddres(string $addres): self
    {
        $this->addres = $addres;

        return $this;
    }

    public function getIdCommende(): ?commande
    {
        return $this->id_commende;
    }

    public function setIdCommende(?commande $id_commende): self
    {
        $this->id_commende = $id_commende;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getDateEntr(): ?\DateTimeInterface
    {
        return $this->date_entr;
    }

    public function setDateEntr(\DateTimeInterface $date_entr): self
    {
        $this->date_entr = $date_entr;

        return $this;
    }

}
