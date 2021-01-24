<?php

namespace App\Entity;

use App\Repository\JobAdRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobAdRepository::class)
 * @ORM\Table(name="job_ads")
 */
class JobAd
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jobTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $jobContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jobCompany;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="jobAdJobCity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobCity;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="jobAdJobPosition")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobPosition;

    /**
     * @ORM\Column(type="boolean")
     */
    private $jobType;

    /**
     * @ORM\Column(type="integer")
     */
    private $jobViews;

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobAdUser")
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

    /**
     * @ORM\ManyToOne(targetEntity=WorkplaceSector::class, inversedBy="jobAdWorkPlaceSector")
     * @ORM\JoinColumn(nullable=true)
     */
    private $workplaceSector;

    public function getId()
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getJobContent(): ?string
    {
        return $this->jobContent;
    }

    public function setJobContent(string $jobContent): self
    {
        $this->jobContent = $jobContent;

        return $this;
    }

    public function getJobCompany(): ?string
    {
        return $this->jobCompany;
    }

    public function setJobCompany(string $jobCompany): self
    {
        $this->jobCompany = $jobCompany;

        return $this;
    }

    public function getJobCity(): ?City
    {
        return $this->jobCity;
    }

    public function setJobCity(?City $jobCity): self
    {
        $this->jobCity = $jobCity;

        return $this;
    }

    public function getJobPosition(): ?Position
    {
        return $this->jobPosition;
    }

    public function setJobPosition(?Position $jobPosition): self
    {
        $this->jobPosition = $jobPosition;

        return $this;
    }

    public function getJobType(): ?bool
    {
        return $this->jobType;
    }

    public function setJobType(bool $jobType): self
    {
        $this->jobType = $jobType;

        return $this;
    }

    public function getJobViews(): ?int
    {
        return $this->jobViews;
    }

    public function setJobViews(int $jobViews): self
    {
        $this->jobViews = $jobViews;

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

    public function getWorkplaceSector(): ?workplaceSector
    {
        return $this->workplaceSector;
    }

    public function setWorkplaceSector(?workplaceSector $workplaceSector): self
    {
        $this->workplaceSector = $workplaceSector;

        return $this;
    }
}
