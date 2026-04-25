<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Photo;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    public function findAllWithUsers(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByUserAndUrls(User $user, array $urls): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->andWhere('p.imageUrl IN (:urls)')
            ->setParameter('user', $user)
            ->setParameter('urls', $urls)
            ->getQuery()
            ->getResult();
    }

    public function saveMany(array $photosEntities): void
    {
        foreach ($photosEntities as $photo) {
            $this->getEntityManager()->persist($photo);
        }
        $this->getEntityManager()->flush();
    }
}
