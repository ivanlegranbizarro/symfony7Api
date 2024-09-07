<?php

namespace App\Entity;

use App\Repository\ComposerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ComposerRepository::class)]
#[UniqueEntity(fields: ['firstName', 'lastName', 'dateOfBirth', 'countryCode'])]
class Composer
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
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['read', 'create', 'update'])]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Date]
    #[Groups(['read', 'create', 'update'])]
    private ?\DateTimeImmutable $dateOfBirth = null;

    #[ORM\Column(length: 2)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 2)]
    #[Groups(['read', 'create', 'update'])]
    private ?string $countryCode = null;

    /**
     * @var Collection<int, Symphony>
     */
    #[ORM\OneToMany(targetEntity: Symphony::class, mappedBy: 'composer', orphanRemoval: true)]
    #[Groups(['read', 'create', 'update'])]
    private Collection $symphonies;

    public function __construct()
    {
        $this->symphonies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeImmutable $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return Collection<int, Symphony>
     */
    public function getSymphonies(): Collection
    {
        return $this->symphonies;
    }

    public function addSymphony(Symphony $symphony): static
    {
        if (!$this->symphonies->contains($symphony)) {
            $this->symphonies->add($symphony);
            $symphony->setComposer($this);
        }

        return $this;
    }

    public function removeSymphony(Symphony $symphony): static
    {
        if ($this->symphonies->removeElement($symphony)) {
            // set the owning side to null (unless already changed)
            if ($symphony->getComposer() === $this) {
                $symphony->setComposer(null);
            }
        }

        return $this;
    }
}
