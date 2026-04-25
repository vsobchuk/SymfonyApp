<?php

namespace Unit\Services;

use App\Entity\Photo;
use App\Entity\User;
use App\Repository\PhotoRepository;
use App\Services\ImportPhotosFromPhoenixService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ImportPhotosFromPhoenixServiceTest extends TestCase
{
    protected HttpClientInterface $httpClient;
    protected PhotoRepository $photoRepository;
    protected ImportPhotosFromPhoenixService $service;
    protected User $user;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->photoRepository = $this->createMock(PhotoRepository::class);
        $this->service = new ImportPhotosFromPhoenixService($this->httpClient, $this->photoRepository);
        $this->user = new User();
    }

    public function testDoImportReturnsCountOfAllNewPhotos(): void
    {
        $this->mockHttpResponse($this->getResponseExampleAsText());

        $this->photoRepository
            ->method('findAllByUserAndUrls')
            ->willReturn([]);

        $this->photoRepository
            ->expects($this->once())
            ->method('saveMany')
            ->with($this->countOf(3));

        $result = $this->service->doImport($this->user);

        $this->assertSame(3, $result);
    }

    public function testDoImportSkipsAlreadyExistingPhotos(): void
    {
        $this->mockHttpResponse($this->getResponseExampleAsText());

        $existingPhoto = new Photo();
        $existingPhoto->setImageUrl('https://images.unsplash.com/photo-1506905925346-21bda4d32df4');

        $this->photoRepository
            ->method('findAllByUserAndUrls')
            ->willReturn([$existingPhoto]);

        $this->photoRepository
            ->expects($this->once())
            ->method('saveMany')
            ->with($this->countOf(2));

        $result = $this->service->doImport($this->user);

        $this->assertSame(2, $result);
    }

    public function testDoImportThrowsExceptionWhenResponseHasNoPhotos(): void
    {
        $this->mockHttpResponse('{"photos": []}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No photos found');

        $this->service->doImport($this->user);
    }

    public function testDoImportSendsAccessTokenHeader(): void
    {
        $this->user->setImportPhotosToken('test-token-123');

        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpResponse->method('getContent')->willReturn('{"photos": []}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                $this->service->getPhotosProviderUrl(),
                $this->callback(fn($options) => ($options['headers']['access-token'] ?? null) === 'test-token-123')
            )
            ->willReturn($httpResponse);

        $this->expectException(\Exception::class);

        $this->service->doImport($this->user);
    }

    private function mockHttpResponse(string $content): void
    {
        $httpResponse = $this->createMock(ResponseInterface::class);
        $httpResponse->method('getContent')->willReturn($content);

        $this->httpClient
            ->method('request')
            ->willReturn($httpResponse);
    }

    protected function getResponseExampleAsText(): string
    {
        return <<<EOT
{
  "photos": [
    {
      "id": 1,
      "description": "Mountain landscape at sunrise with beautiful golden hour lighting",
      "location": "Rocky Mountains, Colorado",
      "settings": "Manual mode, RAW",
      "user_id": 1,
      "photo_url": "https://images.unsplash.com/photo-1506905925346-21bda4d32df4",
      "camera": "Canon EOS R5",
      "lens": "RF 24-70mm f/2.8 L IS USM",
      "focal_length": "35mm",
      "aperture": "f/8",
      "shutter_speed": "1/125",
      "iso": 100,
      "taken_at": "2024-06-15T06:30:00Z"
    },
    {
      "id": 2,
      "description": "Portrait of a tabby cat with striking green eyes",
      "location": "Home Studio",
      "settings": "Aperture priority",
      "user_id": 1,
      "photo_url": "https://images.unsplash.com/photo-1518791841217-8f162f1e1131",
      "camera": "Sony A7 III",
      "lens": "FE 85mm f/1.8",
      "focal_length": "85mm",
      "aperture": "f/2.8",
      "shutter_speed": "1/200",
      "iso": 400,
      "taken_at": "2024-07-20T14:15:00Z"
    },
    {
      "id": 3,
      "description": "Milky Way over a mountain range, astrophotography",
      "location": "Death Valley, California",
      "settings": "Manual mode, Long exposure",
      "user_id": 1,
      "photo_url": "https://images.unsplash.com/photo-1519681393784-d120267933ba",
      "camera": "Canon EOS R5",
      "lens": "RF 15-35mm f/2.8 L IS USM",
      "focal_length": "20mm",
      "aperture": "f/2.8",
      "shutter_speed": "25s",
      "iso": 3200,
      "taken_at": "2024-08-10T03:00:00Z"
    }
  ]
}
EOT;
    }
}
