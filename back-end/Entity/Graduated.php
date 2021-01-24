<?php

namespace App\Entity;

use App\Repository\GraduatedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="graduateds")
 * @ORM\Entity(repositoryClass=GraduatedRepository::class)
 */
class Graduated
{


    /**
     * @ORM\ManyToOne(targetEntity=University::class, inversedBy="graduatedUniversity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $university;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="graduatedUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $graduationYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $isBusiness;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workplaceName;

    /**
     * @ORM\ManyToOne(targetEntity=WorkplaceSector::class, inversedBy="graduatedWorkplaceSector")
     */
    private $workplaceSector;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $isApproved;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="graduatedWorkingCity")
     */
    private $workingCity;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class, inversedBy="graduatedWorkingPosition")
     */
    private $workingPosition;

    /**
     * @ORM\Column(type="integer")
     */
    private $isPublic;

    public function getId()
    {
        return $this->id;
    }

    public function getUniversity(): ?University
    {
        return $this->university;
    }

    public function setUniversity(?University $university): self
    {
        $this->university = $university;

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

    public function getGraduationYear(): ?int
    {
        return $this->graduationYear;
    }

    public function setGraduationYear(int $graduationYear): self
    {
        $this->graduationYear = $graduationYear;

        return $this;
    }

    public function getIsBusiness(): ?int
    {
        return $this->isBusiness;
    }

    public function setIsBusiness(int $isBusiness): self
    {
        $this->isBusiness = $isBusiness;

        return $this;
    }

    public function getWorkplaceName(): ?string
    {
        return $this->workplaceName;
    }

    public function setWorkplaceName(?string $workplaceName): self
    {
        $this->workplaceName = $workplaceName;

        return $this;
    }

    public function getWorkplaceSector(): ?WorkplaceSector
    {
        return $this->workplaceSector;
    }

    public function setWorkplaceSector(?WorkplaceSector $workplaceSector): self
    {
        $this->workplaceSector = $workplaceSector;

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

    public function getIsApproved(): ?int
    {
        return $this->isApproved;
    }

    public function setIsApproved(int $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getWorkingCity(): ?City
    {
        return $this->workingCity;
    }

    public function setWorkingCity(?City $workingCity): self
    {
        $this->workingCity = $workingCity;

        return $this;
    }

    public function getWorkingPosition(): ?Position
    {
        return $this->workingPosition;
    }

    public function setWorkingPosition(?Position $workingPosition): self
    {
        $this->workingPosition = $workingPosition;

        return $this;
    }

    public function getIsPublic(): ?int
    {
        return $this->isPublic;
    }

    public function setIsPublic(int $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}
