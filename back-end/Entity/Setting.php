<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 */
class Setting
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $smtpHost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $smtpPort;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $smtpUsername;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $smtpPassword;

    public function getId()
    {
        return $this->id;
    }

    public function getSmtpHost(): ?string
    {
        return $this->smtpHost;
    }

    public function setSmtpHost(?string $smtpHost): self
    {
        $this->smtpHost = $smtpHost;

        return $this;
    }

    public function getSmtpPort(): ?int
    {
        return $this->smtpPort;
    }

    public function setSmtpPort(?int $smtpPort): self
    {
        $this->smtpPort = $smtpPort;

        return $this;
    }

    public function getSmtpUsername(): ?string
    {
        return $this->smtpUsername;
    }

    public function setSmtpUsername(?string $smtpUsername): self
    {
        $this->smtpUsername = $smtpUsername;

        return $this;
    }

    public function getSmtpPassword(): ?string
    {
        return $this->smtpPassword;
    }

    public function setSmtpPassword(?string $smtpPassword): self
    {
        $this->smtpPassword = $smtpPassword;

        return $this;
    }
}
