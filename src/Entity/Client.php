<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;


#[ORM\Entity(repositoryClass: ClientRepository::class)]


class Client
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;



    #[ORM\Column(length: 200)]
    private ?string $firstname = null;


    private ?string $lastname = null;


    #[ORM\Column(length: 200)]


    private ?string $address = null;


    #[ORM\Column(length: 200)]

    private ?string $phonenumber =null;

    #[ORM\Column(length: 200)]

    private ?string $email = null;

    #[ORM\Column(length: 200)]
    private ?string $password = null;





    public function getId(): ?int
    {
        return $this->id;
    }









    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

}
