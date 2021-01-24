<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 * @ORM\Table(name="blogs")
 */
class Blog
{


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $blogTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $blogContent;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

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
     * @ORM\OneToMany(targetEntity=BlogImage::class, mappedBy="blog")
     */
    private $blogImageBlog;

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

    public function __construct()
    {
        $this->blogImageBlog = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getBlogTitle(): ?string
    {
        return $this->blogTitle;
    }

    public function setBlogTitle(string $blogTitle): self
    {
        $this->blogTitle = $blogTitle;

        return $this;
    }

    public function getBlogContent(): ?string
    {
        return $this->blogContent;
    }

    public function setBlogContent(string $blogContent): self
    {
        $this->blogContent = $blogContent;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

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
     * @return Collection|BlogImage[]
     */
    public function getBlogImageBlog(): Collection
    {
        return $this->blogImageBlog;
    }

    public function addBlogImageBlog(BlogImage $blogImageBlog): self
    {
        if (!$this->blogImageBlog->contains($blogImageBlog)) {
            $this->blogImageBlog[] = $blogImageBlog;
            $blogImageBlog->setBlog($this);
        }

        return $this;
    }

    public function removeBlogImageBlog(BlogImage $blogImageBlog): self
    {
        if ($this->blogImageBlog->removeElement($blogImageBlog)) {
            // set the owning side to null (unless already changed)
            if ($blogImageBlog->getBlog() === $this) {
                $blogImageBlog->setBlog(null);
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
}
