<?php

namespace App\Entity;

use App\Repository\UserSettingRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSettingRepository::class)]
class UserSetting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private bool $allowDuplicatedUrls = false;

    #[ORM\Column]
    private bool $allowLinkAlias = false;

    #[ORM\Column]
    private bool $allowRedirectAfterNewLink = true;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isAllowDuplicatedUrls(): bool
    {
        return $this->allowDuplicatedUrls;
    }

    public function setAllowDuplicatedUrls(bool $allowDuplicatedUrls): static
    {
        $this->allowDuplicatedUrls = $allowDuplicatedUrls;

        return $this;
    }

    public function isAllowLinkAlias(): bool
    {
        return $this->allowLinkAlias;
    }

    public function setAllowLinkAlias(bool $allowLinkAlias): static
    {
        $this->allowLinkAlias = $allowLinkAlias;

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

    public function allowRedirectAfterNewLink(): bool
    {
        return $this->allowRedirectAfterNewLink;
    }

    public function setAllowRedirectAfterNewLink(bool $allowRedirectAfterNewLink): static
    {
        $this->allowRedirectAfterNewLink = $allowRedirectAfterNewLink;

        return $this;
    }
}
