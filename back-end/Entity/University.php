<?php

namespace App\Entity;

use App\Repository\UniversityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="universitys")
 * @ORM\Entity(repositoryClass=UniversityRepository::class)
 */
class University
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $universityName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $universityContent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $universityLogo;

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
     * @ORM\OneToMany(targetEntity=UniversityManager::class, mappedBy="university", orphanRemoval=true)
     */
    private $universityManagerUniversity;



    /**
     * @ORM\OneToMany(targetEntity=Graduated::class, mappedBy="university", orphanRemoval=true)
     */
    private $graduatedUniversity;

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="university")
     */
    private $studentUniversity;

    /**
     * @ORM\OneToMany(targetEntity=UniversityPost::class, mappedBy="university")
     */
    private $universityPostUniversity;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="universityCity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $universityCity;

    /**
     * @ORM\OneToMany(targetEntity=Andac::class, mappedBy="university")
     */
    private $andacUniversity;

    /**
     * @ORM\OneToMany(targetEntity=UniversityImage::class, mappedBy="university")
     */
    private $universityImageUniversity;

    public function __construct()
    {
        $this->universityManagerUniversity = new ArrayCollection();
        $this->graduatedUniversity = new ArrayCollection();
        $this->studentUniversity = new ArrayCollection();
        $this->universityPostUniversity = new ArrayCollection();
        $this->andacUniversity = new ArrayCollection();
        $this->universityImageUniversity = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUniversityName(): ?string
    {
        return $this->universityName;
    }

    public function setUniversityName(string $universityName): self
    {
        $this->universityName = $universityName;

        return $this;
    }

    public function getUniversityContent(): ?string
    {
        return $this->universityContent;
    }

    public function setUniversityContent(string $universityContent): self
    {
        $this->universityContent = $universityContent;

        return $this;
    }

    public function getUniversityLogo(): ?string
    {
        return $this->universityLogo;
    }

    public function setUniversityLogo(?string $universityLogo): self
    {
        $this->universityLogo = $universityLogo;

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

    /**
     * @return Collection|UniversityManager[]
     */
    public function getUniversityManagerUniversity(): Collection
    {
        return $this->universityManagerUniversity;
    }

    public function addUniversityManagerUniversity(UniversityManager $universityManagerUniversity): self
    {
        if (!$this->universityManagerUniversity->contains($universityManagerUniversity)) {
            $this->universityManagerUniversity[] = $universityManagerUniversity;
            $universityManagerUniversity->setUniversity($this);
        }

        return $this;
    }

    public function removeUniversityManagerUniversity(UniversityManager $universityManagerUniversity): self
    {
        if ($this->universityManagerUniversity->removeElement($universityManagerUniversity)) {
            // set the owning side to null (unless already changed)
            if ($universityManagerUniversity->getUniversity() === $this) {
                $universityManagerUniversity->setUniversity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Graduated[]
     */
    public function getGraduatedUniversity(): Collection
    {
        return $this->graduatedUniversity;
    }

    public function addGraduatedUniversity(Graduated $graduatedUniversity): self
    {
        if (!$this->graduatedUniversity->contains($graduatedUniversity)) {
            $this->graduatedUniversity[] = $graduatedUniversity;
            $graduatedUniversity->setUniversity($this);
        }

        return $this;
    }

    public function removeGraduatedUniversity(Graduated $graduatedUniversity): self
    {
        if ($this->graduatedUniversity->removeElement($graduatedUniversity)) {
            // set the owning side to null (unless already changed)
            if ($graduatedUniversity->getUniversity() === $this) {
                $graduatedUniversity->setUniversity(null);
            }
        }

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

    /**
     * @return Collection|Student[]
     */
    public function getStudentUniversity(): Collection
    {
        return $this->studentUniversity;
    }

    public function addStudentUniversity(Student $studentUniversity): self
    {
        if (!$this->studentUniversity->contains($studentUniversity)) {
            $this->studentUniversity[] = $studentUniversity;
            $studentUniversity->setUniversity($this);
        }

        return $this;
    }

    public function removeStudentUniversity(Student $studentUniversity): self
    {
        if ($this->studentUniversity->removeElement($studentUniversity)) {
            // set the owning side to null (unless already changed)
            if ($studentUniversity->getUniversity() === $this) {
                $studentUniversity->setUniversity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UniversityPost[]
     */
    public function getUniversityPostUniversity(): Collection
    {
        return $this->universityPostUniversity;
    }

    public function addUniversityPostUniversity(UniversityPost $universityPostUniversity): self
    {
        if (!$this->universityPostUniversity->contains($universityPostUniversity)) {
            $this->universityPostUniversity[] = $universityPostUniversity;
            $universityPostUniversity->setUniversity($this);
        }

        return $this;
    }

    public function removeUniversityPostUniversity(UniversityPost $universityPostUniversity): self
    {
        if ($this->universityPostUniversity->removeElement($universityPostUniversity)) {
            // set the owning side to null (unless already changed)
            if ($universityPostUniversity->getUniversity() === $this) {
                $universityPostUniversity->setUniversity(null);
            }
        }

        return $this;
    }

    public function getUniversityCity(): ?City
    {
        return $this->universityCity;
    }

    public function setUniversityCity(?City $universityCity): self
    {
        $this->universityCity = $universityCity;

        return $this;
    }

    /**
     * @return Collection|Andac[]
     */
    public function getAndacUniversity(): Collection
    {
        return $this->andacUniversity;
    }

    public function addAndacUniversity(Andac $andacUniversity): self
    {
        if (!$this->andacUniversity->contains($andacUniversity)) {
            $this->andacUniversity[] = $andacUniversity;
            $andacUniversity->setUniversity($this);
        }

        return $this;
    }

    public function removeAndacUniversity(Andac $andacUniversity): self
    {
        if ($this->andacUniversity->removeElement($andacUniversity)) {
            // set the owning side to null (unless already changed)
            if ($andacUniversity->getUniversity() === $this) {
                $andacUniversity->setUniversity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UniversityImage[]
     */
    public function getUniversityImageUniversity(): Collection
    {
        return $this->universityImageUniversity;
    }

    public function addUniversityImageUniversity(UniversityImage $universityImageUniversity): self
    {
        if (!$this->universityImageUniversity->contains($universityImageUniversity)) {
            $this->universityImageUniversity[] = $universityImageUniversity;
            $universityImageUniversity->setUniversity($this);
        }

        return $this;
    }

    public function removeUniversityImageUniversity(UniversityImage $universityImageUniversity): self
    {
        if ($this->universityImageUniversity->removeElement($universityImageUniversity)) {
            // set the owning side to null (unless already changed)
            if ($universityImageUniversity->getUniversity() === $this) {
                $universityImageUniversity->setUniversity(null);
            }
        }

        return $this;
    }
}
