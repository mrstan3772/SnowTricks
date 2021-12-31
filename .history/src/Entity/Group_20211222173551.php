<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
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
    private $group_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $group_creation_date;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="trick_group")
     */
    private $group_tricks;

    public function __construct()
    {
        $this->group_tricks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): self
    {
        $this->group_name = $group_name;

        return $this;
    }

    public function getGroupCreationDate(): ?\DateTimeInterface
    {
        return $this->group_creation_date;
    }

    public function setGroupCreationDate(\DateTimeInterface $group_creation_date): self
    {
        $this->group_creation_date = $group_creation_date;

        return $this;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getGroupTricks(): Collection
    {
        return $this->group_tricks;
    }

    public function addGroupTrick(Trick $groupTrick): self
    {
        if (!$this->group_tricks->contains($groupTrick)) {
            $this->group_tricks[] = $groupTrick;
            $groupTrick->setTrickGroup($this);
        }

        return $this;
    }

    public function removeGroupTrick(Trick $groupTrick): self
    {
        if ($this->group_tricks->removeElement($groupTrick)) {
            // set the owning side to null (unless already changed)
            if ($groupTrick->getTrickGroup() === $this) {
                $groupTrick->setTrickGroup(null);
            }
        }

        return $this;
    }
}
