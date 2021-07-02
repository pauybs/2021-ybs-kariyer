<?php

namespace App\Entity;

use App\Repository\EvaluationMessageRepository;
use App\Traits\DoctrineIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationMessageRepository::class)
 * @ORM\Table(name="evaluation_messages")
 */
class EvaluationMessage
{

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="evaluationMessageBlog")
     */
    private $blog;

    /**
     * @ORM\ManyToOne(targetEntity=Graduated::class, inversedBy="evaluationMessageGraduated")
     */
    private $graduated;

    /**
     * @ORM\ManyToOne(targetEntity=InternAd::class, inversedBy="evaluationMessageInternAd")
     */
    private $internAd;

    /**
     * @ORM\ManyToOne(targetEntity=JobAd::class, inversedBy="evaluationMessageJobAd")
     */
    private $jobAd;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="evaluationMessageQuestion")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="evaluationMessageStudent")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=universityManagerApplication::class, inversedBy="evaluationMessageUniversityManagerApplication")
     */
    private $universityManagerApplication;


    public function getId()
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function getGraduated(): ?Graduated
    {
        return $this->graduated;
    }

    public function setGraduated(?Graduated $graduated): self
    {
        $this->graduated = $graduated;

        return $this;
    }

    public function getInternAd(): ?InternAd
    {
        return $this->internAd;
    }

    public function setInternAd(?InternAd $internAd): self
    {
        $this->internAd = $internAd;

        return $this;
    }

    public function getJobAd(): ?JobAd
    {
        return $this->jobAd;
    }

    public function setJobAd(?JobAd $jobAd): self
    {
        $this->jobAd = $jobAd;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getUniversityManagerApplication(): ?universityManagerApplication
    {
        return $this->universityManagerApplication;
    }

    public function setUniversityManagerApplication(?universityManagerApplication $universityManagerApplication): self
    {
        $this->universityManagerApplication = $universityManagerApplication;

        return $this;
    }

}
