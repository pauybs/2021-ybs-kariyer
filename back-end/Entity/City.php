<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ORM\Table(name="citys")
 */
class City
{


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cityName;

    /**
     * @ORM\Column(type="integer")
     */
    private $cityCode;

    /**
     * @ORM\OneToMany(targetEntity=University::class, mappedBy="universityCity")
     */
    private $universityCity;

    /**
     * @ORM\OneToMany(targetEntity=Graduated::class, mappedBy="workingCity")
     */
    private $graduatedWorkingCity;

    /**
     * @ORM\OneToMany(targetEntity=JobAd::class, mappedBy="jobCity")
     */
    private $jobAdJobCity;

    /**
     * @ORM\OneToMany(targetEntity=InternAd::class, mappedBy="internCity")
     */
    private $internAdCity;

    public function __construct()
    {
        $this->universityCity = new ArrayCollection();
        $this->graduatedWorkingCity = new ArrayCollection();
        $this->jobAdJobCity = new ArrayCollection();
        $this->internAdCity = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): self
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getCityCode(): ?int
    {
        return $this->cityCode;
    }

    public function setCityCode(int $cityCode): self
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * @return Collection|University[]
     */
    public function getUniversityCity(): Collection
    {
        return $this->universityCity;
    }

    public function addUniversityCity(University $universityCity): self
    {
        if (!$this->universityCity->contains($universityCity)) {
            $this->universityCity[] = $universityCity;
            $universityCity->setUniversityCity($this);
        }

        return $this;
    }

    public function removeUniversityCity(University $universityCity): self
    {
        if ($this->universityCity->removeElement($universityCity)) {
            // set the owning side to null (unless already changed)
            if ($universityCity->getUniversityCity() === $this) {
                $universityCity->setUniversityCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Graduated[]
     */
    public function getGraduatedWorkingCity(): Collection
    {
        return $this->graduatedWorkingCity;
    }

    public function addGraduatedWorkingCity(Graduated $graduatedWorkingCity): self
    {
        if (!$this->graduatedWorkingCity->contains($graduatedWorkingCity)) {
            $this->graduatedWorkingCity[] = $graduatedWorkingCity;
            $graduatedWorkingCity->setWorkingCity($this);
        }

        return $this;
    }

    public function removeGraduatedWorkingCity(Graduated $graduatedWorkingCity): self
    {
        if ($this->graduatedWorkingCity->removeElement($graduatedWorkingCity)) {
            // set the owning side to null (unless already changed)
            if ($graduatedWorkingCity->getWorkingCity() === $this) {
                $graduatedWorkingCity->setWorkingCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobAd[]
     */
    public function getJobAdJobCity(): Collection
    {
        return $this->jobAdJobCity;
    }

    public function addJobAdJobCity(JobAd $jobAdJobCity): self
    {
        if (!$this->jobAdJobCity->contains($jobAdJobCity)) {
            $this->jobAdJobCity[] = $jobAdJobCity;
            $jobAdJobCity->setJobCity($this);
        }

        return $this;
    }

    public function removeJobAdJobCity(JobAd $jobAdJobCity): self
    {
        if ($this->jobAdJobCity->removeElement($jobAdJobCity)) {
            // set the owning side to null (unless already changed)
            if ($jobAdJobCity->getJobCity() === $this) {
                $jobAdJobCity->setJobCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InternAd[]
     */
    public function getInternAdCity(): Collection
    {
        return $this->internAdCity;
    }

    public function addInternAdCity(InternAd $internAdCity): self
    {
        if (!$this->internAdCity->contains($internAdCity)) {
            $this->internAdCity[] = $internAdCity;
            $internAdCity->setInternCity($this);
        }

        return $this;
    }

    public function removeInternAdCity(InternAd $internAdCity): self
    {
        if ($this->internAdCity->removeElement($internAdCity)) {
            // set the owning side to null (unless already changed)
            if ($internAdCity->getInternCity() === $this) {
                $internAdCity->setInternCity(null);
            }
        }

        return $this;
    }
}
