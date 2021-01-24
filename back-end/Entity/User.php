<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="integer")
     */
    private $point;

    /**
     * @ORM\Column(type="integer")
     */
    private $isVerified;

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
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="user")
     */
    private $logUser;

    /**
     * @ORM\OneToMany(targetEntity=UserVerified::class, mappedBy="user", orphanRemoval=true)
     */
    private $userVerifiedUser;

    /**
     * @ORM\OneToMany(targetEntity=Point::class, mappedBy="user", orphanRemoval=true)
     */
    private $pointUser;

    /**
     * @ORM\OneToMany(targetEntity=UniversityManager::class, mappedBy="manager", orphanRemoval=true)
     */
    private $universityManagerUser;



    /**
     * @ORM\OneToMany(targetEntity=Graduated::class, mappedBy="user", orphanRemoval=true)
     */
    private $graduatedUser;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="user")
     */
    private $studentUser;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="user")
     */
    private $questionUser;

    /**
     * @ORM\OneToMany(targetEntity=QuestionAnswer::class, mappedBy="user")
     */
    private $questionAnswerUser;

    /**
     * @ORM\OneToMany(targetEntity=JobAd::class, mappedBy="user")
     */
    private $jobAdUser;

    /**
     * @ORM\OneToMany(targetEntity=InternAd::class, mappedBy="user")
     */
    private $internAdUser;

    /**
     * @ORM\OneToMany(targetEntity=UserMeta::class, mappedBy="user")
     */
    private $userMetaUser;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="user")
     */
    private $blogUser;

    /**
     * @ORM\OneToMany(targetEntity=Andac::class, mappedBy="ownerUser")
     */
    private $andacOwnerUser;

    /**
     * @ORM\OneToMany(targetEntity=Andac::class, mappedBy="writerUser")
     */
    private $andacWriterUser;

    /**
     * @ORM\OneToMany(targetEntity=UserImage::class, mappedBy="user")
     */
    private $userImageUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted;

    public function __construct()
    {
        $this->logUser = new ArrayCollection();
        $this->userVerifiedUser = new ArrayCollection();
        $this->pointUser = new ArrayCollection();
        $this->universityManagerUser = new ArrayCollection();
        $this->graduatedUser = new ArrayCollection();
        $this->studentUser = new ArrayCollection();
        $this->questionUser = new ArrayCollection();
        $this->questionAnswerUser = new ArrayCollection();
        $this->jobAdUser = new ArrayCollection();
        $this->internAdUser = new ArrayCollection();
        $this->userMetaUser = new ArrayCollection();
        $this->blogUser = new ArrayCollection();
        $this->andacOwnerUser = new ArrayCollection();
        $this->andacWriterUser = new ArrayCollection();
        $this->userImageUser = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsernameProperty (): ?string
    {
        return $this->username;
    }

    public function setUsernameProperty(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getIsVerified(): ?int
    {
        return $this->isVerified;
    }

    public function setIsVerified(int $isVerified): self
    {
        $this->isVerified = $isVerified;

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
     * @return Collection|Log[]
     */
    public function getLogUser(): Collection
    {
        return $this->logUser;
    }

    public function addLogUser(Log $logUser): self
    {
        if (!$this->logUser->contains($logUser)) {
            $this->logUser[] = $logUser;
            $logUser->setUser($this);
        }

        return $this;
    }

    public function removeLogUser(Log $logUser): self
    {
        if ($this->logUser->removeElement($logUser)) {
            // set the owning side to null (unless already changed)
            if ($logUser->getUser() === $this) {
                $logUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserVerified[]
     */
    public function getUserVerifiedUser(): Collection
    {
        return $this->userVerifiedUser;
    }

    public function addUserVerifiedUser(UserVerified $userVerifiedUser): self
    {
        if (!$this->userVerifiedUser->contains($userVerifiedUser)) {
            $this->userVerifiedUser[] = $userVerifiedUser;
            $userVerifiedUser->setUser($this);
        }

        return $this;
    }

    public function removeUserVerifiedUser(UserVerified $userVerifiedUser): self
    {
        if ($this->userVerifiedUser->removeElement($userVerifiedUser)) {
            // set the owning side to null (unless already changed)
            if ($userVerifiedUser->getUser() === $this) {
                $userVerifiedUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Point[]
     */
    public function getPointUser(): Collection
    {
        return $this->pointUser;
    }

    public function addPointUser(Point $pointUser): self
    {
        if (!$this->pointUser->contains($pointUser)) {
            $this->pointUser[] = $pointUser;
            $pointUser->setUser($this);
        }

        return $this;
    }

    public function removePointUser(Point $pointUser): self
    {
        if ($this->pointUser->removeElement($pointUser)) {
            // set the owning side to null (unless already changed)
            if ($pointUser->getUser() === $this) {
                $pointUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UniversityManager[]
     */
    public function getUniversityManagerUser(): Collection
    {
        return $this->universityManagerUser;
    }

    public function addUniversityManagerUser(UniversityManager $universityManagerUser): self
    {
        if (!$this->universityManagerUser->contains($universityManagerUser)) {
            $this->universityManagerUser[] = $universityManagerUser;
            $universityManagerUser->setManager($this);
        }

        return $this;
    }

    public function removeUniversityManagerUser(UniversityManager $universityManagerUser): self
    {
        if ($this->universityManagerUser->removeElement($universityManagerUser)) {
            // set the owning side to null (unless already changed)
            if ($universityManagerUser->getManager() === $this) {
                $universityManagerUser->setManager(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Graduated[]
     */
    public function getGraduatedUser(): Collection
    {
        return $this->graduatedUser;
    }

    public function addGraduatedUser(Graduated $graduatedUser): self
    {
        if (!$this->graduatedUser->contains($graduatedUser)) {
            $this->graduatedUser[] = $graduatedUser;
            $graduatedUser->setUser($this);
        }

        return $this;
    }

    public function removeGraduatedUser(Graduated $graduatedUser): self
    {
        if ($this->graduatedUser->removeElement($graduatedUser)) {
            // set the owning side to null (unless already changed)
            if ($graduatedUser->getUser() === $this) {
                $graduatedUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudentUser(): Collection
    {
        return $this->studentUser;
    }

    public function addStudentUser(Student $studentUser): self
    {
        if (!$this->studentUser->contains($studentUser)) {
            $this->studentUser[] = $studentUser;
            $studentUser->setUser($this);
        }

        return $this;
    }

    public function removeStudentUser(Student $studentUser): self
    {
        if ($this->studentUser->removeElement($studentUser)) {
            // set the owning side to null (unless already changed)
            if ($studentUser->getUser() === $this) {
                $studentUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestionUser(): Collection
    {
        return $this->questionUser;
    }

    public function addQuestionUser(Question $questionUser): self
    {
        if (!$this->questionUser->contains($questionUser)) {
            $this->questionUser[] = $questionUser;
            $questionUser->setUser($this);
        }

        return $this;
    }

    public function removeQuestionUser(Question $questionUser): self
    {
        if ($this->questionUser->removeElement($questionUser)) {
            // set the owning side to null (unless already changed)
            if ($questionUser->getUser() === $this) {
                $questionUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuestionAnswer[]
     */
    public function getQuestionAnswerUser(): Collection
    {
        return $this->questionAnswerUser;
    }

    public function addQuestionAnswerUser(QuestionAnswer $questionAnswerUser): self
    {
        if (!$this->questionAnswerUser->contains($questionAnswerUser)) {
            $this->questionAnswerUser[] = $questionAnswerUser;
            $questionAnswerUser->setUser($this);
        }

        return $this;
    }

    public function removeQuestionAnswerUser(QuestionAnswer $questionAnswerUser): self
    {
        if ($this->questionAnswerUser->removeElement($questionAnswerUser)) {
            // set the owning side to null (unless already changed)
            if ($questionAnswerUser->getUser() === $this) {
                $questionAnswerUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobAd[]
     */
    public function getJobAdUser(): Collection
    {
        return $this->jobAdUser;
    }

    public function addJobAdUser(JobAd $jobAdUser): self
    {
        if (!$this->jobAdUser->contains($jobAdUser)) {
            $this->jobAdUser[] = $jobAdUser;
            $jobAdUser->setUser($this);
        }

        return $this;
    }

    public function removeJobAdUser(JobAd $jobAdUser): self
    {
        if ($this->jobAdUser->removeElement($jobAdUser)) {
            // set the owning side to null (unless already changed)
            if ($jobAdUser->getUser() === $this) {
                $jobAdUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InternAd[]
     */
    public function getInternAdUser(): Collection
    {
        return $this->internAdUser;
    }

    public function addInternAdUser(InternAd $internAdUser): self
    {
        if (!$this->internAdUser->contains($internAdUser)) {
            $this->internAdUser[] = $internAdUser;
            $internAdUser->setUser($this);
        }

        return $this;
    }

    public function removeInternAdUser(InternAd $internAdUser): self
    {
        if ($this->internAdUser->removeElement($internAdUser)) {
            // set the owning side to null (unless already changed)
            if ($internAdUser->getUser() === $this) {
                $internAdUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserMeta[]
     */
    public function getUserMetaUser(): Collection
    {
        return $this->userMetaUser;
    }

    public function addUserMetaUser(UserMeta $userMetaUser): self
    {
        if (!$this->userMetaUser->contains($userMetaUser)) {
            $this->userMetaUser[] = $userMetaUser;
            $userMetaUser->setUser($this);
        }

        return $this;
    }

    public function removeUserMetaUser(UserMeta $userMetaUser): self
    {
        if ($this->userMetaUser->removeElement($userMetaUser)) {
            // set the owning side to null (unless already changed)
            if ($userMetaUser->getUser() === $this) {
                $userMetaUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogUser(): Collection
    {
        return $this->blogUser;
    }

    public function addBlogUser(Blog $blogUser): self
    {
        if (!$this->blogUser->contains($blogUser)) {
            $this->blogUser[] = $blogUser;
            $blogUser->setUser($this);
        }

        return $this;
    }

    public function removeBlogUser(Blog $blogUser): self
    {
        if ($this->blogUser->removeElement($blogUser)) {
            // set the owning side to null (unless already changed)
            if ($blogUser->getUser() === $this) {
                $blogUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Andac[]
     */
    public function getAndacOwnerUser(): Collection
    {
        return $this->andacOwnerUser;
    }

    public function addAndacOwnerUser(Andac $andacOwnerUser): self
    {
        if (!$this->andacOwnerUser->contains($andacOwnerUser)) {
            $this->andacOwnerUser[] = $andacOwnerUser;
            $andacOwnerUser->setOwnerUser($this);
        }

        return $this;
    }

    public function removeAndacOwnerUser(Andac $andacOwnerUser): self
    {
        if ($this->andacOwnerUser->removeElement($andacOwnerUser)) {
            // set the owning side to null (unless already changed)
            if ($andacOwnerUser->getOwnerUser() === $this) {
                $andacOwnerUser->setOwnerUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Andac[]
     */
    public function getAndacWriterUser(): Collection
    {
        return $this->andacWriterUser;
    }

    public function addAndacWriterUser(Andac $andacWriterUser): self
    {
        if (!$this->andacWriterUser->contains($andacWriterUser)) {
            $this->andacWriterUser[] = $andacWriterUser;
            $andacWriterUser->setWriterUser($this);
        }

        return $this;
    }

    public function removeAndacWriterUser(Andac $andacWriterUser): self
    {
        if ($this->andacWriterUser->removeElement($andacWriterUser)) {
            // set the owning side to null (unless already changed)
            if ($andacWriterUser->getWriterUser() === $this) {
                $andacWriterUser->setWriterUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserImage[]
     */
    public function getUserImageUser(): Collection
    {
        return $this->userImageUser;
    }

    public function addUserImageUser(UserImage $userImageUser): self
    {
        if (!$this->userImageUser->contains($userImageUser)) {
            $this->userImageUser[] = $userImageUser;
            $userImageUser->setUser($this);
        }

        return $this;
    }

    public function removeUserImageUser(UserImage $userImageUser): self
    {
        if ($this->userImageUser->removeElement($userImageUser)) {
            // set the owning side to null (unless already changed)
            if ($userImageUser->getUser() === $this) {
                $userImageUser->setUser(null);
            }
        }

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
