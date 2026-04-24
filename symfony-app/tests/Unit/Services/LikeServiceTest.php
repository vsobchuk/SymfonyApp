<?php

namespace Unit\Services;

use App\Entity\Photo;
use App\Repository\LikeRepositoryInterface;
use App\Services\LikeService;
use PHPUnit\Framework\TestCase;

class LikeServiceTest extends TestCase
{
    protected LikeRepositoryInterface $likeRepository;
    protected LikeService $service;
    protected Photo $photo;

    public function setUp(): void
    {
        $this->likeRepository = $this->createMock(LikeRepositoryInterface::class);
        $this->service = new LikeService($this->likeRepository);
        $this->photo = new Photo();
    }

    public function testExecuteSucces()
    {
        $this->likeRepository
            ->expects(self::once())
            ->method('createLike')
            ->with($this->photo);

        $this->likeRepository
            ->expects(self::once())
            ->method('updatePhotoCounter')
            ->with($this->photo, 1);

        $this->service->execute($this->photo);
    }
    public function testExecuteFails()
    {

        $this->likeRepository
            ->expects(self::once())
            ->method('createLike')
            ->with($this->photo);

        $this->likeRepository
            ->expects(self::once())
            ->method('updatePhotoCounter')
            ->with($this->photo, 1)
            ->willThrowException(new \Exception('Something went wrong'));

        try {
            $this->service->execute($this->photo);
        } catch (\Exception $e) {
            self::assertEquals('Something went wrong while liking the photo', $e->getMessage());
        }
    }
}