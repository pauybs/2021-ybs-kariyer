<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="lessons")
 * @ORM\Entity(repositoryClass=LessonRepository::class)
 */
class Lesson
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lessonName;

    /**
     * @ORM\Column(type="text")
     */
    private $lessonContent;

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
     * @ORM\Column(type="text")
     */
    private $slug;

    public function getId()
    {
        return $this->id;
    }

    public function getLessonName(): ?string
    {
        return $this->lessonName;
    }

    public function setLessonName(string $lessonName): self
    {
        $this->lessonName = $lessonName;

        return $this;
    }

    public function getLessonContent(): ?string
    {
        return $this->lessonContent;
    }

    public function setLessonContent(string $lessonContent): self
    {
        $this->lessonContent = $lessonContent;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
