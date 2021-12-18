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

    public function setCommentAuthor(int $comment_author_id): self
    {
        $this->comment_author_id = $comment_author_id;

        return $this;
    }

    public function getCommentTrick(): ?int
    {
        return $this->comment_trick_id;
    }

    public function setCommentTrick(int $comment_trick_id): self
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
}
