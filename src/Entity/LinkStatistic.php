<?php

namespace App\Entity;

use App\Repository\LinkStatisticRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkStatisticRepository::class)]
class LinkStatistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'linkStatistics')]
    private ?Link $link = null;

    #[ORM\Column(length: 100)]
    private ?string $browserName = null;

    #[ORM\Column(length: 20)]
    private ?string $browserLang = null;

    #[ORM\Column(length: 30)]
    private ?string $platform = null;

    #[ORM\Column(length: 150)]
    private ?string $referer = null;

    #[ORM\Column]
    private bool $isMobile = false;

    #[ORM\Column(length: 100)]
    private ?string $ipAddress = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): static
    {
        $this->link = $link;

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

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function setReferer(string $referer): static
    {
        $this->referer = $referer;

        return $this;
    }

    public function isMobile(): bool
    {
        return $this->isMobile;
    }

    public function setIsMobile(bool $isMobile): static
    {
        $this->isMobile = $isMobile;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): static
    {
        $this->ipAddress = $ipAddress;

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
