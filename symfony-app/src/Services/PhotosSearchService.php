<?php

namespace App\Services;

use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request;

class PhotosSearchService
{
    public function __construct(protected PhotoRepository $photoRepository) {}

    public static function buildFilters(Request $request): array
    {
        $photosFilters = [
            'camera' => null,
            'description' => null,
            'location' => null,
            'username' => null,
            'taken_from' => null,
            'taken_to' => null,
        ];
        foreach ($photosFilters as $fieldName => $fieldValue) {
            $photosFilters[$fieldName] = $request->query->get($fieldName, null);
        }

        return $photosFilters;
    }

    public function getFilteredPhotos(array $photosFilters): array
    {
        return $this->photoRepository->findAllWithFilters($photosFilters);
    }
}