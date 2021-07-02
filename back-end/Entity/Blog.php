<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use App\Traits\DoctrineIdTrait;
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

    /**
     * @ORM\OneToMany(targetEntity=EvaluationMessage::class, mappedBy="blog", orphanRemoval=true)
     */
    private $evaluationMessageBlog;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $spotify;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $youtube;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $soundcloud;


    public function __construct()
    {
        $this->blogImageBlog = new ArrayCollection();
        $this->evaluationMessageBlog = new ArrayCollection();
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

    /**
     * @return Collection|EvaluationMessage[]
     */
    public function getEvaluationMessageBlog(): Collection
    {
        return $this->evaluationMessageBlog;
    }

    public function addEvaluationMessageBlog(EvaluationMessage $evaluationMessageBlog): self
    {
        if (!$this->evaluationMessageBlog->contains($evaluationMessageBlog)) {
            $this->evaluationMessageBlog[] = $evaluationMessageBlog;
            $evaluationMessageBlog->setBlog($this);
        }

        return $this;
    }

    public function removeEvaluationMessageBlog(EvaluationMessage $evaluationMessageBlog): self
    {
        if ($this->evaluationMessageBlog->removeElement($evaluationMessageBlog)) {
            // set the owning side to null (unless already changed)
            if ($evaluationMessageBlog->getBlog() === $this) {
                $evaluationMessageBlog->setBlog(null);
            }
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSpotify(): ?string
    {
        return $this->spotify;
    }

    public function setSpotify(?string $spotify): self
    {
        $this->spotify = $spotify;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getSoundcloud(): ?string
    {
        return $this->soundcloud;
    }

    public function setSoundcloud(?string $soundcloud): self
    {
        $this->soundcloud = $soundcloud;

        return $this;
    }
}
