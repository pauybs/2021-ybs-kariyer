<?php

namespace App\Entity;

use App\Repository\AndacRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AndacRepository::class)
 * @ORM\Table(name="andacs")
 */
class Andac
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="andacOwnerUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ownerUser;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="andacWriterUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $writerUser;

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
    private $isDeleted;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=University::class, inversedBy="andacUniversity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $university;

    public function getId()
    {
        return $this->id;
    }

    public function getOwnerUser(): ?User
    {
        return $this->ownerUser;
    }

    public function setOwnerUser(?User $ownerUser): self
    {
        $this->ownerUser = $ownerUser;

        return $this;
    }

    public function getWriterUser(): ?User
    {
        return $this->writerUser;
    }

    public function setWriterUser(?User $writerUser): self
    {
        $this->writerUser = $writerUser;

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

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
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
}
