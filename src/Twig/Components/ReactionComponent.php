<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\DataTransferObject\ReactionDataDto;
use App\Entity\Pin;
use App\Enum\ReactionTypeEnum;
use App\Repository\ReactionRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('reaction_component')]
class ReactionComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public Pin $pin;

    public function __construct(
            private ReactionRepository $reactionRepository
    )
    {
    }

    /**
     * @return array<int, ReactionDataDto>
     */
    public function getReactions() : array
    {
       return $this->reactionRepository->getCountsForPin(pin: $this->pin);
    }

    #[LiveAction]
    public function react(#[LiveArg] string $reactionType): void
    {
        $this->reactionRepository->createReaction(reactionTypeEnum: ReactionTypeEnum::from($reactionType), pin: $this->pin);
    }
}
