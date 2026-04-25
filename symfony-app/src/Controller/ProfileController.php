<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Form\Type\UserTypeImportPhotosToken;
use App\Services\UserImportPhotosTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    public function profile(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return $this->redirectToRoute('home');
        }

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($userId);

        if (!$user) {
            $session->clear();
            return $this->redirectToRoute('home');
        }

        $importPhotosTokenFrom = $this->createForm(UserTypeImportPhotosToken::class, $user);
        $importPhotosTokenFrom->handleRequest($request);
        if ($importPhotosTokenFrom->isSubmitted()) {
            $userRepository->save($user);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'importPhotosTokenFrom' => $importPhotosTokenFrom,
        ]);
    }
}
