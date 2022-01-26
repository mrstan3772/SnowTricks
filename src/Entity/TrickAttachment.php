<?php

namespace App\Entity;

use App\Repository\TrickAttachmentRepository;
use App\Service\UploaderHelper;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Groups("main")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    private $ta_trick_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $ta_type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $ta_filename;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="ta_tricks")
     * @ORM\JoinColumn(name="ta_trick_id", referencedColumnName="id", nullable=false)
     * @Groups("main")
     */
    private $ta_trick;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main", "input"})
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     */
    private $ta_original_filename;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $ta_mime_type;

    public function __construct(Trick $trick)
    {
        $this->ta_trick = $trick;
    }

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

    public function getTaFilename(): ?string
    {
        return $this->ta_filename;
    }

    public function setTaFilename(string $ta_filename): self
    {
        $this->ta_filename = $ta_filename;

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

    public function getTaOriginalFilename(): ?string
    {
        return $this->ta_original_filename;
    }

    public function setTaOriginalFilename(string $ta_original_filename): self
    {
        $this->ta_original_filename = $ta_original_filename;

        return $this;
    }

    public function getTaMimeType(): ?string
    {
        return $this->ta_mime_type;
    }

    public function setTaMimeType(string $ta_mime_type): self
    {
        $this->ta_mime_type = $ta_mime_type;

        return $this;
    }

    /**
    * @Groups("main")
    */
    public function getFilePath(): string
    {
        if (preg_match('/image/', $this->getTaMimeType())) {
            return UploaderHelper::TRICK_IMAGE_REFERENCE . '/' . $this->getTaFilename();
        } else if (preg_match('/video/', $this->getTaMimeType())) {
            return UploaderHelper::TRICK_VIDEO_REFERENCE . '/' . $this->getTaFilename();
        }
    }
}
