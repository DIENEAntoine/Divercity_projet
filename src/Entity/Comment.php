<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Post $post = null;



    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?User $user = null;



    #[Assert\NotBlank(
        message: "Le contenu de la categorie est obligatoire."
    )]
    #[Assert\Length(
        max: 1000,
        maxMessage: "Le commentaire doit contenir au maximum {{ limit }} caractères.",
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;



    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getPost(): ?Post
    {
        return $this->post;
    }



    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }



    public function getUser(): ?User
    {
        return $this->user;
    }



    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }



    public function getContent(): ?string
    {
        return $this->content;
    }



    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }



    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }



    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
