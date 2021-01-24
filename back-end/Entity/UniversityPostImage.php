<?php

namespace App\Entity;

use App\Repository\UniversityPostImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UniversityPostImageRepository::class)
 * @ORM\Table(name="university_post_images")
 */
class UniversityPostImage
{

    /**
     * @ORM\ManyToOne(targetEntity=UniversityPost::class, inversedBy="universityPostImagePost")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getPost(): ?UniversityPost
    {
        return $this->post;
    }

    public function setPost(?UniversityPost $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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
}
