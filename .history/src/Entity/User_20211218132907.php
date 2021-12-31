<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $user_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $user_active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $user_admin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $user_registration_date;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="trick_author")
     */
    private $user_tricks;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="comment_author")
     */
    private $user_comments;

    public function __construct()
    {
        $this->user_tricks = new ArrayCollection();
        $this->user_comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserEmail(): ?string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }

    public function getUserAvatar(): ?string
    {
        return $this->user_avatar;
    }

    public function setUserAvatar(string $user_avatar): self
    {
        $this->user_avatar = $user_avatar;

        return $this;
    }

    public function getUserPassword(): ?string
    {
        return $this->user_password;
    }

    public function setUserPassword(string $user_password): self
    {
        $this->user_password = $user_password;

        return $this;
    }

    public function getUserToken(): ?string
    {
        return $this->user_token;
    }

    public function setUserToken(string $user_token): self
    {
        $this->user_token = $user_token;

        return $this;
    }

    public function getUserActive(): ?bool
    {
        return $this->user_active;
    }

    public function setUserActive(bool $user_active): self
    {
        $this->user_active = $user_active;

        return $this;
    }

    public function getUserAdmin(): ?bool
    {
        return $this->user_admin;
    }

    public function setUserAdmin(bool $user_admin): self
    {
        $this->user_admin = $user_admin;

        return $this;
    }

    public function getUserRegistrationDate(): ?\DateTimeInterface
    {
        return $this->user_registration_date;
    }

    public function setUserRegistrationDate(\DateTimeInterface $user_registration_date): self
    {
        $this->user_registration_date = $user_registration_date;

        return $this;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getUserTricks(): Collection
    {
        return $this->user_tricks;
    }

    public function addUserTrick(Trick $userTrick): self
    {
        if (!$this->user_tricks->contains($userTrick)) {
            $this->user_tricks[] = $userTrick;
            $userTrick->setTrickAuthor($this);
        }

        return $this;
    }

    public function removeUserTrick(Trick $userTrick): self
    {
        if ($this->user_tricks->removeElement($userTrick)) {
            // set the owning side to null (unless already changed)
            if ($userTrick->getTrickAuthor() === $this) {
                $userTrick->setTrickAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getUserComments(): Collection
    {
        return $this->user_comments;
    }

    public function addUserComment(Comment $userComment): self
    {
        if (!$this->user_comments->contains($userComment)) {
            $this->user_comments[] = $userComment;
            $userComment->setCommentAuthor($this);
        }

        return $this;
    }

    public function removeUserComment(Comment $userComment): self
    {
        if ($this->user_comments->removeElement($userComment)) {
            // set the owning side to null (unless already changed)
            if ($userComment->getCommentAuthor() === $this) {
                $userComment->setCommentAuthor(null);
            }
        }

        return $this;
    }
}