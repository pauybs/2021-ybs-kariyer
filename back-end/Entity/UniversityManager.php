<?php

namespace App\Entity;

use App\Repository\UniversityManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="university_managers")
 * @ORM\Entity(repositoryClass=UniversityManagerRepository::class)
 */
class UniversityManager
{

    /**
     * @ORM\ManyToOne(targetEntity=University::class, inversedBy="universityManagerUniversity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $university;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="universityManagerUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;

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
     * @ORM\OneToMany(targetEntity=UniversityPost::class, mappedBy="manager")
     */
    private $universityPostManager;

    public function __construct()
    {
        $this->universityPostManager = new ArrayCollection();
    }

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

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

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
     * @return Collection|UniversityPost[]
     */
    public function getUniversityPostManager(): Collection
    {
        return $this->universityPostManager;
    }

    public function addUniversityPostManager(UniversityPost $universityPostManager): self
    {
        if (!$this->universityPostManager->contains($universityPostManager)) {
            $this->universityPostManager[] = $universityPostManager;
            $universityPostManager->setManager($this);
        }

        return $this;
    }

    public function removeUniversityPostManager(UniversityPost $universityPostManager): self
    {
        if ($this->universityPostManager->removeElement($universityPostManager)) {
            // set the owning side to null (unless already changed)
            if ($universityPostManager->getManager() === $this) {
                $universityPostManager->setManager(null);
            }
        }

        return $this;
    }
}
