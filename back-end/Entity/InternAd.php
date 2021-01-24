<?php

namespace App\Entity;

use App\Repository\InternAdRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InternAdRepository::class)
 * @ORM\Table(name="intern_ads")
 */
class InternAd
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $internTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $internContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $internCompany;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="internAdCity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $internCity;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="internAdPosition")
     */
    private $internPosition;

    /**
     * @ORM\ManyToOne(targetEntity=WorkplaceSector::class, inversedBy="internType")
     */
    private $workplaceSector;

    /**
     * @ORM\Column(type="boolean")
     */
    private $internType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $internViews;

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="internAdUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getId()
    {
        return $this->id;
    }

    public function getInternTitle(): ?string
    {
        return $this->internTitle;
    }

    public function setInternTitle(string $internTitle): self
    {
        $this->internTitle = $internTitle;

        return $this;
    }

    public function getInternContent(): ?string
    {
        return $this->internContent;
    }

    public function setInternContent(string $internContent): self
    {
        $this->internContent = $internContent;

        return $this;
    }

    public function getInternCompany(): ?string
    {
        return $this->internCompany;
    }

    public function setInternCompany(?string $internCompany): self
    {
        $this->internCompany = $internCompany;

        return $this;
    }

    public function getInternCity(): ?city
    {
        return $this->internCity;
    }

    public function setInternCity(?city $internCity): self
    {
        $this->internCity = $internCity;

        return $this;
    }

    public function getInternPosition(): ?position
    {
        return $this->internPosition;
    }

    public function setInternPosition(?position $internPosition): self
    {
        $this->internPosition = $internPosition;

        return $this;
    }

    public function getWorkplaceSector(): ?workplaceSector
    {
        return $this->workplaceSector;
    }

    public function setWorkplaceSector(?workplaceSector $workplaceSector): self
    {
        $this->workplaceSector = $workplaceSector;

        return $this;
    }

    public function getInternType(): ?bool
    {
        return $this->internType;
    }

    public function setInternType(bool $internType): self
    {
        $this->internType = $internType;

        return $this;
    }

    public function getInternViews(): ?int
    {
        return $this->internViews;
    }

    public function setInternViews(?int $internViews): self
    {
        $this->internViews = $internViews;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
