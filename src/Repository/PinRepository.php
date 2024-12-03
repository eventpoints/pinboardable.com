<?php

declare(strict_types=1);

namespace App\Repository;

use App\DataTransferObject\PinFilterDto;
use App\Entity\Pin;
use App\Entity\Tag;
use App\Enum\PinTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\DBAL\Types\Types;
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

    public function save(Pin $entity, bool $flush = false): void
    {
        $this->getEntityManager()
                ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                    ->flush();
        }
    }

    public function remove(Pin $entity, bool $flush = false): void
    {
        $this->getEntityManager()
                ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                    ->flush();
        }
    }

    /**
     * @return Query|array<int, Pin>
     */
    public function findByFilter(PinFilterDto $pinFilterDto, bool $isQuery = false): Query|array
    {
        $qb = $this->createQueryBuilder('pin');

        if (!empty($pinFilterDto->getKeyword())) {
            $qb->andWhere(
                    $qb->expr()->like('LOWER(pin.title)', ':keyword')
            );

            $qb->orWhere(
                    $qb->expr()->like('LOWER(pin.description)', ':keyword')
            )->setParameter('keyword', '%' . strtolower($pinFilterDto->getKeyword()) . '%');
        }

        if ($pinFilterDto->getPinTypeEnum() instanceof PinTypeEnum) {
            $qb->andWhere(
                    $qb->expr()->eq('pin.pinTypeEnum', ':type')
            )->setParameter('type', $pinFilterDto->getPinTypeEnum());
        }

        if ($pinFilterDto->getTags()->count() > 0) {
            $qb->join('pin.tags', 'tag');
            $ids = $pinFilterDto->getTags()->map(fn(Tag $tag) => $tag->getId())->toArray();
            $qb->andWhere(
                    $qb->expr()->in('tag.id', ':ids')
            )->setParameter('ids', $ids);
        }


        $qb->orderBy('pin.createdAt', Order::Ascending->value);

        if ($isQuery) {
            return $qb->getQuery();
        }

        return $qb->getQuery()->getResult();
    }
}
