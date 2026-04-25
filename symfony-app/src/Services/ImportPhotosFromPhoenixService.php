<?php

namespace App\Services;

use App\Entity\Photo;
use App\Entity\User;
use App\Repository\PhotoRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportPhotosFromPhoenixService
{
    //@todo this should go to the configuration - different env may have different URLs
    protected string $photosProviderUrl = 'http://phoenix:4000/api/photos';

    public function __construct(
        private HttpClientInterface $httpClient,
        private PhotoRepository $photoRepository
    ) {}

    public function doImport(User $user): void
    {
        $response = $this->httpClient->request(
            'GET',
            $this->photosProviderUrl,
            ['headers' => ['access-token' => $user->getImportPhotosToken()]]
        );

        $response = json_decode($response->getContent(), true);
        if (empty($response['photos'])) {
            throw new \Exception('No photos found');
        }

        $response = array_combine(array_column($response['photos'], 'photo_url'), $response['photos']);
        $existingPhotos = $this->photoRepository->findAllByUserAndUrls($user, array_keys($response));
        foreach ($existingPhotos as $existingPhoto) {
            unset($response[$existingPhoto->getImageUrl()]);
        }
        //@todo if there are no new images to import, stop and show user message

        $photos = [];
        foreach ($response as $photoData) {
            $photo = new Photo();
            $photo->setUser($user)
                ->setImageUrl($photoData['photo_url'])
                ->setLocation($photoData['location'])
                ->setDescription($photoData['description'])
                ->setCamera($photoData['camera'])
                ->setLikeCounter(0)
                ->setTakenAt(new \DateTimeImmutable($photoData['taken_at']));
            $photos[] = $photo;
        }
        if (!empty($photos)) {
            $this->photoRepository->saveMany($photos);
        }
    }
}