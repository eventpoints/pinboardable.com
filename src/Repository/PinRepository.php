<?php

declare(strict_types=1);

namespace App\Repository;

use App\DataTransferObject\PinFilterDto;
use App\Entity\Pin;
use App\Enum\PinTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pin>
 */
class PinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pin::class);
    }

    /**
     * @return Query|array<int, Pin>
     */
    public function findByFilter(PinFilterDto $pinFilterDto, bool $isQuery = false): Query|array
    {
        $qb = $this->createQueryBuilder('pin');

        if (! empty($pinFilterDto->getKeyword())) {
            $qb->andWhere(
                $qb->expr()->like('pin.title', ':keyword')
            );

            $qb->orWhere(
                $qb->expr()->like('pin.description', ':keyword')
            )->setParameter('keyword', '%' . $pinFilterDto->getKeyword() . '%');
        }

        if ($pinFilterDto->getPinTypeEnum() instanceof PinTypeEnum) {
            $qb->andWhere(
                $qb->expr()->eq('pin.pinTypeEnum', ':type')
            )->setParameter('type', $pinFilterDto->getPinTypeEnum() );
        }

        $qb->orderBy('pin.createdAt', Order::Ascending->value);

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()->getResult();
    }
}
