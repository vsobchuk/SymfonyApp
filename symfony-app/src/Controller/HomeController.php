<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Services\PhotosSearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: 'home')]
    public function index(
        Request $request,
        PhotosSearchService $photosSearchService,
        EntityManagerInterface $em,
    ): Response {
        $likeRepository = $em->getRepository(Like::class);

        $photosFilters = PhotosSearchService::buildFilters($request);
        $photos = $photosSearchService->getFilteredPhotos($photosFilters);

        $session = $request->getSession();
        $userId = $session->get('user_id');
        $currentUser = null;
        $userLikes = [];

        if ($userId) {
            $currentUser = $em->getRepository(User::class)->find($userId);

            if ($currentUser) {
                foreach ($photos as $photo) {
                    $likeRepository->setUser($currentUser);
                    $userLikes[$photo->getId()] = $likeRepository->hasUserLikedPhoto($photo);
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'photos' => $photos,
            'currentUser' => $currentUser,
            'userLikes' => $userLikes,
            'photosFilters' => $photosFilters
        ]);
    }
}
