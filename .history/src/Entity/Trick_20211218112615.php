<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="adresses")
     */
    private $trick_author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $trick_name;

    /**
     * @ORM\Column(type="string", length=10000)
     */
    private $trick_description;

    /**
     * @ORM\Column(type="integer")
     */
    private $trick_group;

    /**
     * @ORM\Column(type="datetime")
     */
    private $trick_creation_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $trick_update_date;

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

    public function getTrickGroup(): ?int
    {
        return $this->trick_group;
    }

    public function setTrickGroup(int $trick_group): self
    {
        $this->trick_group = $trick_group;

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
}
