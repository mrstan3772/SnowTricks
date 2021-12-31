<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $comment_content;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_author_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_trick_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $comment_creation_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="user_comments")
     * @ORM\JoinColumn(name="comment_author_id", referencedColumnName="id", nullable=false)
     */
    private $comment_author;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="trick_comments")
     * @ORM\JoinColumn(name="comment_trick_id", referencedColumnName="id",nullable=false)
     */
    private $comment_trick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentContent(): ?string
    {
        return $this->comment_content;
    }

    public function setCommentContent(string $comment_content): self
    {
        $this->comment_content = $comment_content;

        return $this;
    }

    public function getCommentAuthorId(): ?int
    {
        return $this->comment_author_id;
    }

    public function setCommentAuthorId(int $comment_author_id): self
    {
        $this->comment_author_id = $comment_author_id;

        return $this;
    }

    public function getCommentTrickId(): ?int
    {
        return $this->comment_trick_id;
    }

    public function setCommentTrickId(int $comment_trick_id): self
    {
        $this->comment_trick_id = $comment_trick_id;

        return $this;
    }

    public function getCommentCreationDate(): ?\DateTimeInterface
    {
        return $this->comment_creation_date;
    }

    public function setCommentCreationDate(\DateTimeInterface $comment_creation_date): self
    {
        $this->comment_creation_date = $comment_creation_date;

        return $this;
    }

    public function getCommentAuthor(): ?User
    {
        return $this->comment_author;
    }

    public function setCommentAuthor(?User $comment_author): self
    {
        $this->comment_author = $comment_author;

        return $this;
    }

    public function getCommentTrick(): ?Trick
    {
        return $this->comment_trick;
    }

    public function setCommentTrick(?Trick $comment_trick): self
    {
        $this->comment_trick = $comment_trick;

        return $this;
    }
}
