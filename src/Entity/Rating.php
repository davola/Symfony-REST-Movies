<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies:write"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"movies:write"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"movies:write"})
     */
    private $movie;

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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $Movie): self
    {
        $this->movie = $Movie;

        return $this;
    }
}
