<?php

namespace App\Entity;

use App\Repository\WorkplaceSectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="workplace_sectors")
 * @ORM\Entity(repositoryClass=WorkplaceSectorRepository::class)
 */
class WorkplaceSector
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sectorName;

    /**
     * @ORM\OneToMany(targetEntity=Graduated::class, mappedBy="workplaceSector")
     */
    private $graduatedWorkplaceSector;

    /**
     * @ORM\OneToMany(targetEntity=JobAd::class, mappedBy="workplaceSector")
     */
    private $jobAdWorkPlaceSector;

    /**
     * @ORM\OneToMany(targetEntity=InternAd::class, mappedBy="workplaceSector")
     */
    private $internType;

    public function __construct()
    {
        $this->graduatedWorkplaceSector = new ArrayCollection();
        $this->jobAdWorkPlaceSector = new ArrayCollection();
        $this->internType = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSectorName(): ?string
    {
        return $this->sectorName;
    }

    public function setSectorName(string $sectorName): self
    {
        $this->sectorName = $sectorName;

        return $this;
    }

    /**
     * @return Collection|Graduated[]
     */
    public function getGraduatedWorkplaceSector(): Collection
    {
        return $this->graduatedWorkplaceSector;
    }

    public function addGraduatedWorkplaceSector(Graduated $graduatedWorkplaceSector): self
    {
        if (!$this->graduatedWorkplaceSector->contains($graduatedWorkplaceSector)) {
            $this->graduatedWorkplaceSector[] = $graduatedWorkplaceSector;
            $graduatedWorkplaceSector->setWorkplaceSector($this);
        }

        return $this;
    }

    public function removeGraduatedWorkplaceSector(Graduated $graduatedWorkplaceSector): self
    {
        if ($this->graduatedWorkplaceSector->removeElement($graduatedWorkplaceSector)) {
            // set the owning side to null (unless already changed)
            if ($graduatedWorkplaceSector->getWorkplaceSector() === $this) {
                $graduatedWorkplaceSector->setWorkplaceSector(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobAd[]
     */
    public function getJobAdWorkPlaceSector(): Collection
    {
        return $this->jobAdWorkPlaceSector;
    }

    public function addJobAdWorkPlaceSector(JobAd $jobAdWorkPlaceSector): self
    {
        if (!$this->jobAdWorkPlaceSector->contains($jobAdWorkPlaceSector)) {
            $this->jobAdWorkPlaceSector[] = $jobAdWorkPlaceSector;
            $jobAdWorkPlaceSector->setWorkplaceSector($this);
        }

        return $this;
    }

    public function removeJobAdWorkPlaceSector(JobAd $jobAdWorkPlaceSector): self
    {
        if ($this->jobAdWorkPlaceSector->removeElement($jobAdWorkPlaceSector)) {
            // set the owning side to null (unless already changed)
            if ($jobAdWorkPlaceSector->getWorkplaceSector() === $this) {
                $jobAdWorkPlaceSector->setWorkplaceSector(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InternAd[]
     */
    public function getInternType(): Collection
    {
        return $this->internType;
    }

    public function addInternType(InternAd $internType): self
    {
        if (!$this->internType->contains($internType)) {
            $this->internType[] = $internType;
            $internType->setWorkplaceSector($this);
        }

        return $this;
    }

    public function removeInternType(InternAd $internType): self
    {
        if ($this->internType->removeElement($internType)) {
            // set the owning side to null (unless already changed)
            if ($internType->getWorkplaceSector() === $this) {
                $internType->setWorkplaceSector(null);
            }
        }

        return $this;
    }
}
