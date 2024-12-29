<?php

namespace App\Entity;

use App\Repository\SocialProfileStatisticsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialProfileStatisticsRepository::class)]
class SocialProfileStatistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'socialProfileStatistics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SocialProfile $socialProfile = null;

    #[ORM\ManyToOne(inversedBy: 'socialProfileStatistics')]
    private ?User $user = null;

    #[ORM\Column(length: 50)]
    private ?string $browserName = null;

    #[ORM\Column(length: 30)]
    private ?string $browserLang = null;

    #[ORM\Column(length: 50)]
    private ?string $platform = null;

    #[ORM\Column]
    private bool $isMobil = false;

    #[ORM\Column(length: 150)]
    private ?string $ipAdress = null;

    #[ORM\Column]
    private bool $isSeen = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocialProfile(): ?SocialProfile
    {
        return $this->socialProfile;
    }

    public function setSocialProfile(?SocialProfile $socialProfile): static
    {
        $this->socialProfile = $socialProfile;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBrowserName(): ?string
    {
        return $this->browserName;
    }

    public function setBrowserName(string $browserName): static
    {
        $this->browserName = $browserName;

        return $this;
    }

    public function getBrowserLang(): ?string
    {
        return $this->browserLang;
    }

    public function setBrowserLang(string $browserLang): static
    {
        $this->browserLang = $browserLang;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function isMobil(): bool
    {
        return $this->isMobil;
    }

    public function setMobil(bool $isMobil): static
    {
        $this->isMobil = $isMobil;

        return $this;
    }

    public function getIpAdress(): ?string
    {
        return $this->ipAdress;
    }

    public function setIpAdress(string $ipAdress): static
    {
        $this->ipAdress = $ipAdress;

        return $this;
    }

    public function isSeen(): bool
    {
        return $this->isSeen;
    }

    public function setSeen(bool $isSeen): static
    {
        $this->isSeen = $isSeen;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
