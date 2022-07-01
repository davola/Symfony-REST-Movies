<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class)
     */
    private $Casts;

    /**
     * @ORM\ManyToOne(targetEntity=Director::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Director;

    /**
     * @ORM\OneToMany(targetEntity=Rating::class, mappedBy="Movie", orphanRemoval=true)
     */
    private $ratings;

    public function __construct()
    {
        $this->Casts = new ArrayCollection();
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
        return $this->Casts;
    }

    public function addCast(Actor $cast): self
    {
        if (!$this->Casts->contains($cast)) {
            $this->Casts[] = $cast;
        }

        return $this;
    }

    public function removeCast(Actor $cast): self
    {
        $this->Casts->removeElement($cast);

        return $this;
    }

    public function getDirector(): ?Director
    {
        return $this->Director;
    }

    public function setDirector(?Director $Director): self
    {
        $this->Director = $Director;

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
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
}
