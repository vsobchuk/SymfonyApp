<?php

namespace Unit\Repository;

use App\Entity\Photo;
use App\Entity\User;
use App\Repository\PhotoRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class PhotoRepositoryTest extends TestCase
{
    private PhotoRepository $repository;
    private QueryBuilder $queryBuilder;
    private AbstractQuery $query;

    protected function setUp(): void
    {
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->query = $this->createMock(AbstractQuery::class);

        $this->repository = $this->getMockBuilder(PhotoRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();

        $this->repository->method('createQueryBuilder')->willReturn($this->queryBuilder);

        $this->queryBuilder->method('leftJoin')->willReturnSelf();
        $this->queryBuilder->method('addSelect')->willReturnSelf();
        $this->queryBuilder->method('orderBy')->willReturnSelf();
        $this->queryBuilder->method('where')->willReturnSelf();
        $this->queryBuilder->method('andWhere')->willReturnSelf();
        $this->queryBuilder->method('setParameter')->willReturnSelf();
        $this->queryBuilder->method('getQuery')->willReturn($this->query);
    }

    public function testFindAllWithFiltersCamera(): void
    {
        $this->query->method('getResult')->willReturn([]);

        $this->queryBuilder
            ->expects(self::once())
            ->method('andWhere')
            ->with('LOWER(p.camera) LIKE :camera');
        $this->repository->findAllWithFilters(['camera' => 'test_camera']);
    }

    public function testFindAllWithFiltersUser(): void
    {
        $this->query->method('getResult')->willReturn([]);

        $this->queryBuilder
            ->expects(self::once())
            ->method('andWhere')
            ->with('LOWER(u.name) LIKE :username OR LOWER(u.lastName) LIKE :username');
        $this->repository->findAllWithFilters(['username' => 'test_username']);
    }

    public function testFindAllWithFiltersAllSet(): void
    {
        $this->query->method('getResult')->willReturn([]);

        $capturedConditions = [];
        $this->queryBuilder
            ->method('andWhere')
            ->willReturnCallback(function (string $condition) use (&$capturedConditions) {
                $capturedConditions[] = $condition;
                return $this->queryBuilder;
            });

        $this->repository->findAllWithFilters([
            'camera' => 'test_camera',
            'description' => 'test_description',
            'location' => 'test_location',
            'username' => 'test_username',
            'taken_from' => '2024-01-01',
            'taken_to' => '2024-12-31',
        ]);

        $this->assertSame([
            'LOWER(p.camera) LIKE :camera',
            'LOWER(p.description) LIKE :description',
            'LOWER(p.location) LIKE :location',
            'p.takenAt >= :takenFrom',
            'p.takenAt <= :takenTo',
            'LOWER(u.name) LIKE :username OR LOWER(u.lastName) LIKE :username',
        ], $capturedConditions);
    }

    public function testFindAllWithFiltersNoneSet(): void
    {
        $this->query->method('getResult')->willReturn([]);

        $this->queryBuilder
            ->expects($this->exactly(0))
            ->method('andWhere');
        $this->repository->findAllWithFilters([]);
    }

    public function testFindAllByUserAndUrls(): void
    {
        $user = $this->createMock(User::class);
        $urls = ['test0', 'test1'];

        $this->query->method('getResult')->willReturn([]);

        $this->queryBuilder
            ->expects(self::once())
            ->method('where')
            ->with('p.user = :user');
        $this->queryBuilder
            ->expects(self::once())
            ->method('andWhere')
            ->with('p.imageUrl IN (:urls)');
        $this->queryBuilder
            ->expects($this->exactly(2))
            ->method('setParameter');

        $result = $this->repository->findAllByUserAndUrls($user, $urls);
    }
}
