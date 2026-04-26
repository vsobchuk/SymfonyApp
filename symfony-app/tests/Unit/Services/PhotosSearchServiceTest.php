<?php

namespace Unit\Services;

use App\Repository\PhotoRepository;
use App\Services\PhotosSearchService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PhotosSearchServiceTest extends TestCase
{
    protected PhotoRepository $photoRepository;
    protected PhotosSearchService $service;

    public function setUp(): void
    {
        $this->photoRepository = $this->createMock(PhotoRepository::class);
        $this->service = new PhotosSearchService($this->photoRepository);
    }

    public function testBuildFiltersEmpty()
    {
        $request = new Request();
        $photosFilters = PhotosSearchService::buildFilters($request);
        foreach ($photosFilters as $photosFilter) {
            $this->assertEmpty($photosFilter);
        }
    }

    public function testBuildFiltersNotEmpty()
    {
        $request = new Request([
            'camera' => 'test_camera',
            'description' => 'test_description',
            'location' => 'test_location',
            'username' => 'test_username',
            'taken_from' => date('Y-m-d'),
            'taken_to' => date('Y-m-d'),
        ]);
        $photosFilters = PhotosSearchService::buildFilters($request);
        foreach ($photosFilters as $photosFilter) {
            $this->assertNotEmpty($photosFilter);
        }
    }

    public function testGetFilteredPhotos()
    {

        $request = new Request([
            'camera' => 'test_camera',
            'description' => 'test_description',
            'location' => 'test_location',
            'username' => 'test_username',
            'taken_from' => date('Y-m-d'),
            'taken_to' => date('Y-m-d'),
        ]);
        $photosFilters = PhotosSearchService::buildFilters($request);

        $this->photoRepository
            ->expects(self::once())
            ->method('findAllWithFilters')
            ->with($photosFilters);

        $this->service->getFilteredPhotos($photosFilters);
    }
}