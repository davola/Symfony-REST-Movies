<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Groups({"movies:read", "movies:write"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"movies:read", "movies:write"})
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/[0-9]+\.[0-9]+/", message="The rating value has to be a decimal number")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"movies:write"})
     * @Assert\NotNull()
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
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
