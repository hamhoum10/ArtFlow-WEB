<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Article::class )]
    #[ORM\JoinColumn(name: 'id_article', referencedColumnName: 'id_article')]
    private ?Article $id_article = null;

    #[ORM\ManyToOne(targetEntity: Client::class )]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'id')]
    private ?Client $id_user = null;

    #[ORM\Column]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdUser(): ?Artiste
    {
        return $this->id_user;
    }

    public function setIdUser(?Client $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
