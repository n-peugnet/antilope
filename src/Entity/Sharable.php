<?php

namespace App\Entity;

use App\Repository\SharableRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SharableRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Sharable
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $beginAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sharables")
     */
    private $managedBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=UserClass::class)
     */
    private $visibleBy;

    /**
     * @ORM\OneToMany(targetEntity=Validation::class, mappedBy="sharable")
     */
    private $validations;

    public function __construct()
    {
        $this->managedBy = new ArrayCollection();
        $this->validations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime();
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(?\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getManagedBy(): Collection
    {
        return $this->managedBy;
    }

    public function addManagedBy(User $managedBy): self
    {
        if (!$this->managedBy->contains($managedBy)) {
            $this->managedBy[] = $managedBy;
        }

        return $this;
    }

    public function removeManagedBy(User $managedBy): self
    {
        $this->managedBy->removeElement($managedBy);

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVisibleBy(): ?UserClass
    {
        return $this->visibleBy;
    }

    public function setVisibleBy(?UserClass $visibleBy): self
    {
        $this->visibleBy = $visibleBy;

        return $this;
    }

    /**
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations[] = $validation;
            $validation->setSharable($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getSharable() === $this) {
                $validation->setSharable(null);
            }
        }

        return $this;
    }
}
