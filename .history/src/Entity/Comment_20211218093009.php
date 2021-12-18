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
     * @ORM\Column(type="string", length=255)
     */
    private $comment_content;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_author;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_trick;

    /**
     * @ORM\Column(type="datetime")
     */
    private $comment_creation_date;

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

    public function getCommentAuthor(): ?int
    {
        return $this->comment_author;
    }

    public function setCommentAuthor(int $comment_author): self
    {
        $this->comment_author = $comment_author;

        return $this;
    }

    public function getCommentTrick(): ?int
    {
        return $this->comment_trick;
    }

    public function setCommentTrick(int $comment_trick): self
    {
        $this->comment_trick = $comment_trick;

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
}
