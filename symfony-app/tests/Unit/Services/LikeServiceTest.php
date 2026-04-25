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

    public function testLikePhotoSucces()
    {
        $this->likeRepository
            ->expects(self::once())
            ->method('createLike')
            ->with($this->photo);

        $this->likeRepository
            ->expects(self::once())
            ->method('updateLikeCounter')
            ->with($this->photo, 1);

        $this->service->likePhoto($this->photo);
    }
    public function testLikePhotoFails()
    {

        $this->likeRepository
            ->expects(self::once())
            ->method('createLike')
            ->with($this->photo);

        $this->likeRepository
            ->expects(self::once())
            ->method('updateLikeCounter')
            ->with($this->photo, 1)
            ->willThrowException(new \Exception('Something went wrong'));

        try {
            $this->service->likePhoto($this->photo);
        } catch (\Exception $e) {
            self::assertEquals('Something went wrong while liking the photo', $e->getMessage());
        }
    }
}