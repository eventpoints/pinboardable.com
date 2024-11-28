<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->persist($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()
            ->remove($entity);

        if ($flush) {
            $this->getEntityManager()
                ->flush();
        }
    }

    /**
     * Finds a user by email or creates a new one.
     *
     * @param string $email The email to search for
     * @param callable|null $createCallback Optional callback to configure the new User entity
     * @return User The found or newly created User entity
     */
    public function findByEmailOrCreate(string $email, ?callable $createCallback = null): User
    {
        $user = $this->findOneBy([
            'email' => $email,
        ]);

        if (! $user) {
            $user = new User(email: $email);

            if ($createCallback) {
                $createCallback($user);
            }
        }

        return $user;
    }
}
