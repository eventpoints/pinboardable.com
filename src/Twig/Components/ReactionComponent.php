<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\DataTransferObject\ReactionDataDto;
use App\Entity\Pin;
use App\Entity\Reaction;
use App\Enum\ReactionTypeEnum;
use App\Repository\ReactionRepository;
use App\Service\FingerPrint\FingerPrintService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
            private readonly ReactionRepository $reactionRepository,
            private readonly FingerPrintService $fingerPrintService,
            private readonly RequestStack       $requestStack
    )
    {
    }

    /**
     * @return array<int, ReactionDataDto>
     */
    public function getReactions(): array
    {
        return $this->reactionRepository->getCountsForPin(pin: $this->pin);
    }

    #[LiveAction]
    public function react(#[LiveArg] string $reactionType): void
    {
        $fingerprint = $this->fingerPrintService->generate(request: $this->requestStack->getCurrentRequest());
        if (!$this->isChecked($reactionType)) {
            $this->reactionRepository->createReaction(reactionTypeEnum: ReactionTypeEnum::from($reactionType), pin: $this->pin, fingerprint: $fingerprint);
        } else {
            $reaction = $this->reactionRepository->findOneBy(['pin' => $this->pin, 'fignerprint' => $fingerprint, 'reactionTypeEnum' => ReactionTypeEnum::from($reactionType)]);
            $this->reactionRepository->remove(entity: $reaction, flush: true);
        }
    }

    #[LiveAction]
    public function isChecked(#[LiveArg] string $reactionType): bool
    {
        $fingerprint = $this->fingerPrintService->generate(request: $this->requestStack->getCurrentRequest());
        return $this->pin->getReactions()->exists(fn(int $key, Reaction $reaction) => $reaction->getFignerprint() === $fingerprint && $reaction->getReactionTypeEnum() === ReactionTypeEnum::from($reactionType));
    }
}
