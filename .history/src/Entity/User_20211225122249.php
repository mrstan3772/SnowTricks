<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username","email"},message="Il existe déjà un compte avec ce nom d'utilisateur ou cette adresse e-mail.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

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

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

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
        return $this->username;
    }

    public function setUserName(string $username): self
    {
        $this->username = $username;

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

    public function getUserAvatar(): ?string
    {
        return $this->user_avatar;
    }

    public function setUserAvatar(string $user_avatar): self
    {
        $this->user_avatar = $user_avatar;

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

    public function getUserRegistrationDate(): ?\DateTimeInterface
    {
        return $this->user_registration_date;
    }

    public function setUserRegistrationDate(\DateTimeInterface $user_registration_date): self
    {
        $this->user_registration_date = $user_registration_date;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // il est obligatoire d'avoir au moins un rôle si on est authentifié, par convention c'est ROLE_USER
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
