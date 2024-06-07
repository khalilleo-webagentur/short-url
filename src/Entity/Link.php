<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkRepository::class)]
class Link
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'links')]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: LinkStatistic::class, mappedBy: 'link')]
    private Collection $linkStatistics;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $token = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private int $counter = 0;

    #[ORM\Column]
    private bool $isFave = false;

    #[ORM\Column]
    private bool $isPublic = true;

    #[ORM\Column]
    private bool $isReported = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->linkStatistics = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $Url): static
    {
        $this->url = $Url;

        return $this;
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): static
    {
        $this->counter = $counter;

        return $this;
    }

    public function isFave(): bool
    {
        return $this->isFave;
    }

    public function setIsFave(bool $isFave): static
    {
        $this->isFave = $isFave;

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

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function isReported(): bool
    {
        return $this->isReported;
    }

    public function setIsReported(bool $isReported): static
    {
        $this->isReported = $isReported;

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

    /**
     * @return Collection<int, LinkStatistic>
     */
    public function getLinkStatistics(): Collection
    {
        return $this->linkStatistics;
    }

    public function addLinkStatistic(LinkStatistic $linkStatistic): static
    {
        if (!$this->linkStatistics->contains($linkStatistic)) {
            $this->linkStatistics->add($linkStatistic);
            $linkStatistic->setLink($this);
        }

        return $this;
    }

    public function removeLinkStatistic(LinkStatistic $linkStatistic): static
    {
        if ($this->linkStatistics->removeElement($linkStatistic)) {
            // set the owning side to null (unless already changed)
            if ($linkStatistic->getLink() === $this) {
                $linkStatistic->setLink(null);
            }
        }

        return $this;
    }
}
