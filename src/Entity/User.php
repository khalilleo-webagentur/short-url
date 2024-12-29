<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $token = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column]
    private bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: Link::class, mappedBy: 'user')]
    private Collection $links;

    /**
     * @var Collection<int, UserSetting>
     */
    #[ORM\OneToMany(targetEntity: UserSetting::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $userSettings;

    /**
     * @var Collection<int, TempUser>
     */
    #[ORM\OneToMany(targetEntity: TempUser::class, mappedBy: 'user')]
    private Collection $tempUsers;

    /**
     * @var Collection<int, LinkCollection>
     */
    #[ORM\OneToMany(targetEntity: LinkCollection::class, mappedBy: 'user')]
    private Collection $linkCollections;

    /**
     * @var Collection<int, SocialProfile>
     */
    #[ORM\OneToMany(targetEntity: SocialProfile::class, mappedBy: 'user')]
    private Collection $socialProfiles;

    /**
     * @var Collection<int, SocialProfileVisitor>
     */
    #[ORM\OneToMany(targetEntity: SocialProfileVisitor::class, mappedBy: 'user')]
    private Collection $socialProfileVisitors;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    /**
     * @var Collection<int, SocialProfileStatistics>
     */
    #[ORM\OneToMany(targetEntity: SocialProfileStatistics::class, mappedBy: 'user')]
    private Collection $socialProfileStatistics;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->links = new ArrayCollection();
        $this->userSettings = new ArrayCollection();
        $this->tempUsers = new ArrayCollection();
        $this->linkCollections = new ArrayCollection();
        $this->socialProfiles = new ArrayCollection();
        $this->socialProfileVisitors = new ArrayCollection();
        $this->socialProfileStatistics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        //
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

    /**
     * @return Collection<int, Link>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Link $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setUser($this);
        }

        return $this;
    }

    public function removeLink(Link $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getUser() === $this) {
                $link->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserSetting>
     */
    public function getUserSettings(): Collection
    {
        return $this->userSettings;
    }

    public function addUserSetting(UserSetting $userSetting): static
    {
        if (!$this->userSettings->contains($userSetting)) {
            $this->userSettings->add($userSetting);
            $userSetting->setUser($this);
        }

        return $this;
    }

    public function removeUserSetting(UserSetting $userSetting): static
    {
        if ($this->userSettings->removeElement($userSetting)) {
            // set the owning side to null (unless already changed)
            if ($userSetting->getUser() === $this) {
                $userSetting->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TempUser>
     */
    public function getTempUsers(): Collection
    {
        return $this->tempUsers;
    }

    public function addTempUser(TempUser $tempUser): static
    {
        if (!$this->tempUsers->contains($tempUser)) {
            $this->tempUsers->add($tempUser);
            $tempUser->setUser($this);
        }

        return $this;
    }

    public function removeTempUser(TempUser $tempUser): static
    {
        if ($this->tempUsers->removeElement($tempUser)) {
            // set the owning side to null (unless already changed)
            if ($tempUser->getUser() === $this) {
                $tempUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LinkCollection>
     */
    public function getLinkCollections(): Collection
    {
        return $this->linkCollections;
    }

    public function addLinkCollection(LinkCollection $linkCollection): static
    {
        if (!$this->linkCollections->contains($linkCollection)) {
            $this->linkCollections->add($linkCollection);
            $linkCollection->setUser($this);
        }

        return $this;
    }

    public function removeLinkCollection(LinkCollection $linkCollection): static
    {
        if ($this->linkCollections->removeElement($linkCollection)) {
            // set the owning side to null (unless already changed)
            if ($linkCollection->getUser() === $this) {
                $linkCollection->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialProfile>
     */
    public function getSocialProfiles(): Collection
    {
        return $this->socialProfiles;
    }

    public function addSocialProfile(SocialProfile $socialProfile): static
    {
        if (!$this->socialProfiles->contains($socialProfile)) {
            $this->socialProfiles->add($socialProfile);
            $socialProfile->setUser($this);
        }

        return $this;
    }

    public function removeSocialProfile(SocialProfile $socialProfile): static
    {
        if ($this->socialProfiles->removeElement($socialProfile)) {
            // set the owning side to null (unless already changed)
            if ($socialProfile->getUser() === $this) {
                $socialProfile->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialProfileVisitor>
     */
    public function getSocialProfileVisitors(): Collection
    {
        return $this->socialProfileVisitors;
    }

    public function addSocialProfileVisitor(SocialProfileVisitor $socialProfileVisitor): static
    {
        if (!$this->socialProfileVisitors->contains($socialProfileVisitor)) {
            $this->socialProfileVisitors->add($socialProfileVisitor);
            $socialProfileVisitor->setUser($this);
        }

        return $this;
    }

    public function removeSocialProfileVisitor(SocialProfileVisitor $socialProfileVisitor): static
    {
        if ($this->socialProfileVisitors->removeElement($socialProfileVisitor)) {
            // set the owning side to null (unless already changed)
            if ($socialProfileVisitor->getUser() === $this) {
                $socialProfileVisitor->setUser(null);
            }
        }

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        // set the owning side of the relation if necessary
        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, SocialProfileStatistics>
     */
    public function getSocialProfileStatistics(): Collection
    {
        return $this->socialProfileStatistics;
    }

    public function addSocialProfileStatistic(SocialProfileStatistics $socialProfileStatistic): static
    {
        if (!$this->socialProfileStatistics->contains($socialProfileStatistic)) {
            $this->socialProfileStatistics->add($socialProfileStatistic);
            $socialProfileStatistic->setUser($this);
        }

        return $this;
    }

    public function removeSocialProfileStatistic(SocialProfileStatistics $socialProfileStatistic): static
    {
        if ($this->socialProfileStatistics->removeElement($socialProfileStatistic)) {
            // set the owning side to null (unless already changed)
            if ($socialProfileStatistic->getUser() === $this) {
                $socialProfileStatistic->setUser(null);
            }
        }

        return $this;
    }
}
