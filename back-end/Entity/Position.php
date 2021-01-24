<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 * @ORM\Table(name="positions")
 */
class Position
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $positionName;

    /**
     * @ORM\OneToMany(targetEntity=Graduated::class, mappedBy="workingPosition")
     */
    private $graduatedWorkingPosition;

    /**
     * @ORM\OneToMany(targetEntity=JobAd::class, mappedBy="jobPosition")
     */
    private $jobAdJobPosition;

    /**
     * @ORM\OneToMany(targetEntity=InternAd::class, mappedBy="internPosition")
     */
    private $internAdPosition;

    public function __construct()
    {
        $this->graduatedWorkingPosition = new ArrayCollection();
        $this->jobAdJobPosition = new ArrayCollection();
        $this->internAdPosition = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPositionName(): ?string
    {
        return $this->positionName;
    }

    public function setPositionName(string $positionName): self
    {
        $this->positionName = $positionName;

        return $this;
    }

    /**
     * @return Collection|Graduated[]
     */
    public function getGraduatedWorkingPosition(): Collection
    {
        return $this->graduatedWorkingPosition;
    }

    public function addGraduatedWorkingPosition(Graduated $graduatedWorkingPosition): self
    {
        if (!$this->graduatedWorkingPosition->contains($graduatedWorkingPosition)) {
            $this->graduatedWorkingPosition[] = $graduatedWorkingPosition;
            $graduatedWorkingPosition->setWorkingPosition($this);
        }

        return $this;
    }

    public function removeGraduatedWorkingPosition(Graduated $graduatedWorkingPosition): self
    {
        if ($this->graduatedWorkingPosition->removeElement($graduatedWorkingPosition)) {
            // set the owning side to null (unless already changed)
            if ($graduatedWorkingPosition->getWorkingPosition() === $this) {
                $graduatedWorkingPosition->setWorkingPosition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobAd[]
     */
    public function getJobAdJobPosition(): Collection
    {
        return $this->jobAdJobPosition;
    }

    public function addJobAdJobPosition(JobAd $jobAdJobPosition): self
    {
        if (!$this->jobAdJobPosition->contains($jobAdJobPosition)) {
            $this->jobAdJobPosition[] = $jobAdJobPosition;
            $jobAdJobPosition->setJobPosition($this);
        }

        return $this;
    }

    public function removeJobAdJobPosition(JobAd $jobAdJobPosition): self
    {
        if ($this->jobAdJobPosition->removeElement($jobAdJobPosition)) {
            // set the owning side to null (unless already changed)
            if ($jobAdJobPosition->getJobPosition() === $this) {
                $jobAdJobPosition->setJobPosition(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InternAd[]
     */
    public function getInternAdPosition(): Collection
    {
        return $this->internAdPosition;
    }

    public function addInternAdPosition(InternAd $internAdPosition): self
    {
        if (!$this->internAdPosition->contains($internAdPosition)) {
            $this->internAdPosition[] = $internAdPosition;
            $internAdPosition->setInternPosition($this);
        }

        return $this;
    }

    public function removeInternAdPosition(InternAd $internAdPosition): self
    {
        if ($this->internAdPosition->removeElement($internAdPosition)) {
            // set the owning side to null (unless already changed)
            if ($internAdPosition->getInternPosition() === $this) {
                $internAdPosition->setInternPosition(null);
            }
        }

        return $this;
    }
}
