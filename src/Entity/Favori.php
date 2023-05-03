<?php

namespace App\Entity;

use App\Repository\FavoriRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriRepository::class)]
class Favori
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class )]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Client $id_user=Null;


    #[ORM\ManyToOne(targetEntity: Article::class )]
    #[ORM\JoinColumn(name: 'id_article', referencedColumnName: 'id_article')]
    private ?Article $id_article=Null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?Artiste
    {
        return $this->id_user;
    }

    public function setIdUser(?Client $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdArticle(): ?Article
    {
        return $this->id_article;
    }

    public function setIdArticle(?Article $id_article): self
    {
        $this->id_article = $id_article;

        return $this;
    }
}
