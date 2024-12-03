<?php

namespace App\Repository;

use App\DataTransferObject\ReactionDataDto;
use App\Entity\Pin;
use App\Entity\Reaction;
use App\Enum\ReactionTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Reaction>
 */
class ReactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reaction::class);
    }

    /**
     * @param Pin $pin
     * @return array<int, ReactionDataDto>
     */
    public function getCountsForPin(Pin $pin): array
    {
        $qb = $this->createQueryBuilder('reaction');
        $qb->select('reaction.reactionTypeEnum AS reactionType, COUNT(reaction.id) AS count');
        $qb->andWhere(
                $qb->expr()->eq('reaction.pin', ':pin')
        )->setParameter('pin', $pin->getId(), 'uuid');

        $qb->groupBy('reaction.reactionTypeEnum');
        $qb->orderBy('count', Order::Descending->value);

        // Fetch the results directly
        $results = $qb->getQuery()->getArrayResult();

        // Prepare a default count map using enum values as keys
        $reactionCounts = [];
        foreach (ReactionTypeEnum::cases() as $type) {
            $reactionCounts[$type->value] = 0; // Initialize counts to 0
        }

        // Populate counts from query results
        foreach ($results as $result) {
            $reactionCounts[$result['reactionType']->value] = $result['count'];
        }

        // Create ReactionDataDto objects
        $reactionDataDtos = [];
        foreach (ReactionTypeEnum::cases() as $type) {
            $reactionDataDtos[] = new ReactionDataDto(
                    type: $type,
                    count: $reactionCounts[$type->value]
            );
        }

        usort($reactionDataDtos, fn(ReactionDataDto $a, ReactionDataDto $b) => $b->count <=> $a->count);

        return $reactionDataDtos;
    }




    public function createReaction(ReactionTypeEnum $reactionTypeEnum, Pin $pin): Reaction
    {
        $reaction = new Reaction(reactionTypeEnum: $reactionTypeEnum, pin: $pin);
        $this->getEntityManager()
                ->persist($reaction);
        $this->getEntityManager()
                ->flush();
        return $reaction;
    }

}
