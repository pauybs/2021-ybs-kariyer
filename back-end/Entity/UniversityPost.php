<?php

namespace App\Entity;

use App\Repository\UniversityPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UniversityPostRepository::class)
 * @ORM\Table(name="university_posts")
 */
class UniversityPost
{

    /**
     * @ORM\ManyToOne(targetEntity=UniversityManager::class, inversedBy="universityPostManager")
     * @ORM\JoinColumn(nullable=false)
     */
    private $manager;

    /**
     * @ORM\ManyToOne(targetEntity=University::class, inversedBy="universityPostUniversity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $university;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="uuid")
     */
    private $postId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $views;

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
     * @ORM\OneToMany(targetEntity=UniversityPostImage::class, mappedBy="post", orphanRemoval=true)
     */
    private $universityPostImagePost;

    public function __construct()
    {
        $this->universityPostImagePost = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getManager(): ?UniversityManager
    {
        return $this->manager;
    }

    public function setManager(?UniversityManager $manager): self
    {
        $this->manager = $manager;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId): self
    {
        $this->postId = $postId;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

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

    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection|UniversityPostImage[]
     */
    public function getUniversityPostImagePost(): Collection
    {
        return $this->universityPostImagePost;
    }

    public function addUniversityPostImagePost(UniversityPostImage $universityPostImagePost): self
    {
        if (!$this->universityPostImagePost->contains($universityPostImagePost)) {
            $this->universityPostImagePost[] = $universityPostImagePost;
            $universityPostImagePost->setPost($this);
        }

        return $this;
    }

    public function removeUniversityPostImagePost(UniversityPostImage $universityPostImagePost): self
    {
        if ($this->universityPostImagePost->removeElement($universityPostImagePost)) {
            // set the owning side to null (unless already changed)
            if ($universityPostImagePost->getPost() === $this) {
                $universityPostImagePost->setPost(null);
            }
        }

        return $this;
    }
}
