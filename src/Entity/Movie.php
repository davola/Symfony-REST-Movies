<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *          "get"={
 *              "access_control"="is_granted('ROLE_USER') and object.getOwner() == user",
 *              "message"="User must be owner"
 *          }
 *      },
 *     normalizationContext={"groups"={"movies:read"}},
 *     denormalizationContext={"groups"={"movies:write"}}
 * )
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"movies:read", "movies:write"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, cascade={"persist"})
     * @Groups({"movies:read"})
     */
    private $casts;

    /**
     * @ORM\ManyToOne(targetEntity=Director::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $director;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="movie", orphanRemoval=true, cascade={"persist"})
     * @Groups({"movies:read"})
     */
    private $ratings;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Serializer\Context({ DateTimeNormalizer::FORMAT_KEY = "d-m-Y" })
     * @Groups({"movies:read", "movies:write"})
     * @Assert\NotNull()
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"movies:read"})
     * @Assert\Valid()
     */
    private $owner;

    public function __construct()
    {
        $this->casts = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Actor>
     */
    public function getCasts(): Collection
    {
        return $this->casts;
    }


    public function setCasts(ArrayCollection $casts): self
    {
        $this->casts = $casts;

        return $this;
    }

    /**
     * @return ArrayCollection<string>
     * @SerializedName("casts")
     * @Groups({"movies:read"})
     */
    public function getCastsSerialized(): ArrayCollection
    {
        $casts = new ArrayCollection();
        foreach ($this->getCasts() as $actor) {
            /** @var Actor $actor */
            $casts->add($actor->getName());
        }

        return $casts;
    }

    public function addCast(Actor $cast): self
    {
        if (!$this->casts->contains($cast)) {
            $this->casts[] = $cast;
        }

        return $this;
    }

    public function removeCast(Actor $cast): self
    {
        $this->casts->removeElement($cast);

        return $this;
    }

    /**
     * @Groups({"movies:write"})
     * @SerializedName("casts")
     */
    public function setCastByArray(array $casts): self
    {
        foreach ($casts as $name) {
            $cast = (new Actor())->setName($name);
            $this->addCast($cast);
        }

        return $this;
    }

    private function getDirector(): ?Director
    {
        return $this->director;
    }

    /**
     * @SerializedName("director")
     * @return string|null
     * @Groups({"movies:read"})
     */
    public function getDirectorName()
    {
        return $this->getDirector()->getName();
    }

    public function setDirector(?Director $Director): self
    {
        $this->director = $Director;

        return $this;
    }

    /**
     * @Groups({"movies:write"})
     * @SerializedName("director")
     */
    public function setDirectorByName(string $name): self
    {
        $director = (new Director())->setName($name);
        $this->setDirector($director);

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    private function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * @SerializedName("ratings")
     * @Groups({"movies:read"})
     */
    public function getRatingsSerialized(): array
    {
        $ratings = [];
        foreach ($this->getRatings() as $rating) {
            /** @var Rating $rating */
            $ratings[$rating->getName()] = $rating->getValue();
        }

        return $ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setMovie($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getMovie() === $this) {
                $rating->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"movies:write"})
     * @SerializedName("ratings")
     */
    public function setRatingByObject(array $ratingObj): self
    {
        foreach ($ratingObj as $name => $value) {
            $rating = (new Rating())->setName($name)->setValue($value);
            $this->addRating($rating);
        }

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): self
    {
        $this->releaseDate = $release_date;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
