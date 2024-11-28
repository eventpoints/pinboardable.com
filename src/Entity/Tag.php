<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private null|Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Pin>
     */
    #[ORM\ManyToMany(targetEntity: Pin::class, inversedBy: 'tags')]
    private Collection $pins;

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->pins = new ArrayCollection();
    }

    public function getId(): null|Uuid
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

    /**
     * @return Collection<int, Pin>
     */
    public function getPins(): Collection
    {
        return $this->pins;
    }

    public function addPin(Pin $pin): static
    {
        if (! $this->pins->contains($pin)) {
            $this->pins->add($pin);
        }

        return $this;
    }

    public function removePin(Pin $pin): static
    {
        $this->pins->removeElement($pin);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
