<?php

namespace App\Entity;

use App\Repository\SymphonyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SymphonyRepository::class)]
#[UniqueEntity(fields: ['title', 'composer', 'dateOfCreation'])]
class Symphony
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['read', 'create', 'update'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000)]
    #[Groups(['read', 'create', 'update'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'symphonies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Assert\Valid]
    #[Groups(['read', 'create', 'update'])]
    private ?Composer $composer = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[Groups(['read', 'create', 'update'])]
    private ?\DateTimeImmutable $dateOfCreation = null;

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

    public function getComposer(): ?Composer
    {
        return $this->composer;
    }

    public function setComposer(?Composer $composer): static
    {
        $this->composer = $composer;

        return $this;
    }

    public function getDateOfCreation(): ?\DateTimeImmutable
    {
        return $this->dateOfCreation;
    }

    public function setDateOfCreation(\DateTimeImmutable $dateOfCreation): static
    {
        $this->dateOfCreation = $dateOfCreation;

        return $this;
    }
}
