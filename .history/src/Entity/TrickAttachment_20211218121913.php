<?php

namespace App\Entity;

use App\Repository\TrickAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickAttachmentRepository::class)
 */
class TrickAttachment
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
    private $ta_trick_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ta_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ta_path;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="ta_tricks")
     * @ORM\JoinColumn(name="trick_group_id", referencedColumnName="id", nullable=false)
     */
    private $ta_trick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaTrickId(): ?int
    {
        return $this->ta_trick_id;
    }

    public function setTaTrickId(int $ta_trick_id): self
    {
        $this->ta_trick_id = $ta_trick_id;

        return $this;
    }

    public function getTaType(): ?string
    {
        return $this->ta_type;
    }

    public function setTaType(string $ta_type): self
    {
        $this->ta_type = $ta_type;

        return $this;
    }

    public function getTaPath(): ?string
    {
        return $this->ta_path;
    }

    public function setTaPath(string $ta_path): self
    {
        $this->ta_path = $ta_path;

        return $this;
    }

    public function getTaTrick(): ?Trick
    {
        return $this->ta_trick;
    }

    public function setTaTrick(?Trick $ta_trick): self
    {
        $this->ta_trick = $ta_trick;

        return $this;
    }
}
