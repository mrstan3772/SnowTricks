<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

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
    private $user_registration;

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

    public function getUserRegistration(): ?\DateTimeInterface
    {
        return $this->user_registration;
    }

    public function setUserRegistration(\DateTimeInterface $user_registration): self
    {
        $this->user_registration = $user_registration;

        return $this;
    }
}
