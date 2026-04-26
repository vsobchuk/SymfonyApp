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

    public function findAllWithFilters(array $filterFields): array {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.user', 'u')
            ->addSelect('u')
            ->orderBy('p.id', 'ASC');

        $filterNames = [
            'camera' => null,
            'description' => null,
            'location' => null,
        ];

        foreach ($filterNames as $fieldName => $value) {
            if (!empty($filterFields[$fieldName])) {
                $qb->andWhere('LOWER(p.' . $fieldName . ') LIKE :' . $fieldName)
                   ->setParameter($fieldName, '%' . strtolower($filterFields[$fieldName]) . '%');
            }
        }

        if (!empty($filterFields['taken_from'])) {
            $qb->andWhere('p.takenAt >= :takenFrom')
               ->setParameter('takenFrom', new \DateTimeImmutable($filterFields['taken_from'] . ' 00:00:00'));
        }
        if (!empty($filterFields['taken_to'])) {
            $qb->andWhere('p.takenAt <= :takenTo')
               ->setParameter('takenTo', new \DateTimeImmutable($filterFields['taken_to'] . ' 00:00:00'));
        }

        if (!empty($filterFields['username'])) {
            $qb->andWhere('LOWER(u.name) LIKE :username OR LOWER(u.lastName) LIKE :username')
               ->setParameter('username', '%' . strtolower($filterFields['username']) . '%');
        }

        return $qb->getQuery()->getResult();
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
