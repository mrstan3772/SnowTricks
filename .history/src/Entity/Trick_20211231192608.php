<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @UniqueEntity(fields="trick_name",message="Il existe déjà un compte avec cette adresse e-mail.",groups={"registration"})
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $trick_author_id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $trick_name;

    /**
     * @ORM\Column(type="string", length=10000)
     */
    private $trick_description;

    /**
     * @ORM\Column(type="integer")
     */
    private $trick_group_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $trick_creation_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $trick_update_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class,inversedBy="user_tricks")
     * @ORM\JoinColumn(name="trick_author_id", referencedColumnName="id", nullable=false)
     */
    private $trick_author;

    /**
     * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="group_tricks")
     * @ORM\JoinColumn(name="trick_group_id", referencedColumnName="id",nullable=false)
     */
    private $trick_group;

    /**
     * @ORM\OneToMany(targetEntity=TrickAttachment::class, mappedBy="ta_trick")
     */
    private $trick_attachments;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="comment_trick")
     */
    private $trick_comments;

    public function __construct()
    {
        $this->trick_attachments = new ArrayCollection();
        $this->trick_comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrickAuthorId(): ?int
    {
        return $this->trick_author_id;
    }

    public function setTrickAuthorId(int $trick_author_id): self
    {
        $this->trick_author_id = $trick_author_id;

        return $this;
    }

    public function getTrickName(): ?string
    {
        return $this->trick_name;
    }

    public function setTrickName(string $trick_name): self
    {
        $this->trick_name = $trick_name;

        return $this;
    }

    public function getTrickDescription(): ?string
    {
        return $this->trick_description;
    }

    public function setTrickDescription(string $trick_description): self
    {
        $this->trick_description = $trick_description;

        return $this;
    }

    public function getTrickGroupId(): ?int
    {
        return $this->trick_group_id;
    }

    public function setTrickGroupId(int $trick_group_id): self
    {
        $this->trick_group_id = $trick_group_id;

        return $this;
    }

    public function getTrickCreationDate(): ?\DateTimeInterface
    {
        return $this->trick_creation_date;
    }

    public function setTrickCreationDate(\DateTimeInterface $trick_creation_date): self
    {
        $this->trick_creation_date = $trick_creation_date;

        return $this;
    }

    public function getTrickUpdateDate(): ?\DateTimeInterface
    {
        return $this->trick_update_date;
    }

    public function setTrickUpdateDate(\DateTimeInterface $trick_update_date): self
    {
        $this->trick_update_date = $trick_update_date;

        return $this;
    }

    public function getTrickAuthor(): ?User
    {
        return $this->trick_author;
    }

    public function setTrickAuthor(?User $trick_author): self
    {
        $this->trick_author = $trick_author;

        return $this;
    }

    public function getTrickGroup(): ?Group
    {
        return $this->trick_group;
    }

    public function setTrickGroup(?Group $trick_group): self
    {
        $this->trick_group = $trick_group;

        return $this;
    }

    /**
     * @return Collection|TrickAttachment[]
     */
    public function getTrickAttachments(): Collection
    {
        return $this->trick_attachments;
    }

    public function addTrickAttachment(TrickAttachment $TrickAttachment): self
    {
        if (!$this->trick_attachments->contains($TrickAttachment)) {
            $this->trick_attachments[] = $TrickAttachment;
            $TrickAttachment->setTaTrick($this);
        }

        return $this;
    }

    public function removeTrickAttachment(TrickAttachment $TrickAttachment): self
    {
        if ($this->trick_attachments->removeElement($TrickAttachment)) {
            // set the owning side to null (unless already changed)
            if ($TrickAttachment->getTaTrick() === $this) {
                $TrickAttachment->setTaTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getTrickComments(): Collection
    {
        return $this->trick_comments;
    }

    public function addTrickComment(Comment $trickComment): self
    {
        if (!$this->trick_comments->contains($trickComment)) {
            $this->trick_comments[] = $trickComment;
            $trickComment->setCommentTrick($this);
        }

        return $this;
    }

    public function removeTrickComment(Comment $trickComment): self
    {
        if ($this->trick_comments->removeElement($trickComment)) {
            // set the owning side to null (unless already changed)
            if ($trickComment->getCommentTrick() === $this) {
                $trickComment->setCommentTrick(null);
            }
        }

        return $this;
    }
}
