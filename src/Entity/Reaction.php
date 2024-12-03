<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ReactionTypeEnum;
use App\Repository\ReactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ReactionRepository::class)]
#[ORM\Index(name:"idx_pin_reaction_type", columns: ["pin_id", "reaction_type_enum"])]
class Reaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid|null $id = null;

    #[ORM\Column(enumType: ReactionTypeEnum::class)]
    private ReactionTypeEnum|null $reactionTypeEnum = null;

    #[ORM\Column(length: 255, nullable: true)]
    private null|string $fignerprint = null;

    #[ORM\ManyToOne(inversedBy: 'reactions')]
    private ?Pin $pin = null;

    /**
     * @param ReactionTypeEnum|null $reactionTypeEnum
     * @param string|null $fignerprint
     * @param Pin|null $pin
     */
    public function __construct(?ReactionTypeEnum $reactionTypeEnum, ?string $fignerprint, ?Pin $pin = null)
    {
        $this->reactionTypeEnum = $reactionTypeEnum;
        $this->fignerprint = $fignerprint;
        $this->pin = $pin;
    }


    public function getId(): Uuid|null
    {
        return $this->id;
    }

    public function getReactionTypeEnum(): ?ReactionTypeEnum
    {
        return $this->reactionTypeEnum;
    }

    public function setReactionTypeEnum(?ReactionTypeEnum $reactionTypeEnum): void
    {
        $this->reactionTypeEnum = $reactionTypeEnum;
    }

    public function getPin(): ?Pin
    {
        return $this->pin;
    }

    public function setPin(?Pin $pin): static
    {
        $this->pin = $pin;

        return $this;
    }

    public function getFignerprint(): ?string
    {
        return $this->fignerprint;
    }

    public function setFignerprint(?string $fignerprint): void
    {
        $this->fignerprint = $fignerprint;
    }

}
