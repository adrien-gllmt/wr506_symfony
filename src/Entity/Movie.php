<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ApiResource(
    normalizationContext: [
        'groups' => ['movie:read'],
    ],
)]
#[Put(security: "is_granted('ROLE_ADMIN') or object.owner == user")]
#[Post(security: "is_granted('ROLE_ADMIN')")]
#[Get]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie:read', 'actor:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    #[Groups(['movie:read', 'actor:read', 'category:read'])]
    #[Assert\NotBlank]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['movie:read', 'actor:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['movie:read', 'actor:read'])]
    #[Assert\NotBlank]
    private ?string $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['movie:read', 'actor:read'])]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column]
    #[Groups(['movie:read'])]
    #[Assert\NotBlank]
    private ?int $entries = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['movie:read'])]
    private ?int $budget = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, mappedBy: 'movies')]
    #[Groups(['movie:read'])]
    #[Assert\NotBlank]
    private Collection $actors;

    #[ORM\ManyToOne(inversedBy: 'movies')]
    #[Groups(['movie:read', 'actor:read'])]
    #[Assert\NotBlank]
    private ?Category $category = null;

    #[ORM\Column(length: 60)]
    #[Groups(['movie:read'])]
    #[Assert\NotBlank]
    private ?string $director = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['movie:read'])]
    private ?int $note = null;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getEntries(): ?int
    {
        return $this->entries;
    }

    public function setEntries(int $entries): static
    {
        $this->entries = $entries;

        return $this;
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(?int $budget): static
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): static
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->addMovie($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): static
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeMovie($this);
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): static
    {
        $this->director = $director;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }
}
