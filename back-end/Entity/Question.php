<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use App\Traits\DoctrineIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="questions")
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    use DoctrineIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $questionTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $questionContent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="questionUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $slug;

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
     * @ORM\OneToMany(targetEntity=QuestionAnswer::class, mappedBy="question")
     */
    private $questionAnswerQuestion;

    public function __construct()
    {
        $this->questionAnswerQuestion = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuestionTitle(): ?string
    {
        return $this->questionTitle;
    }

    public function setQuestionTitle(string $questionTitle): self
    {
        $this->questionTitle = $questionTitle;

        return $this;
    }

    public function getQuestionContent(): ?string
    {
        return $this->questionContent;
    }

    public function setQuestionContent(string $questionContent): self
    {
        $this->questionContent = $questionContent;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
     * @return Collection|QuestionAnswer[]
     */
    public function getQuestionAnswerQuestion(): Collection
    {
        return $this->questionAnswerQuestion;
    }

    public function addQuestionAnswerQuestion(QuestionAnswer $questionAnswerQuestion): self
    {
        if (!$this->questionAnswerQuestion->contains($questionAnswerQuestion)) {
            $this->questionAnswerQuestion[] = $questionAnswerQuestion;
            $questionAnswerQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionAnswerQuestion(QuestionAnswer $questionAnswerQuestion): self
    {
        if ($this->questionAnswerQuestion->removeElement($questionAnswerQuestion)) {
            // set the owning side to null (unless already changed)
            if ($questionAnswerQuestion->getQuestion() === $this) {
                $questionAnswerQuestion->setQuestion(null);
            }
        }

        return $this;
    }
}
