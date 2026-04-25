<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserImportPhotosForm;
use App\Form\Type\UserTypeImportPhotosTokenForm;
use App\Services\ImportPhotosFromPhoenixService;
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
        EntityManagerInterface $em,
        ImportPhotosFromPhoenixService $importPhotosService
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

        $importPhotosTokenFrom = $this->createForm(UserTypeImportPhotosTokenForm::class, $user);
        $importPhotosTokenFrom->handleRequest($request);
        if ($importPhotosTokenFrom->isSubmitted()) {
            $userRepository->save($user);
        }

        $requestImportPhotosForm = $this->createForm(UserImportPhotosForm::class);
        $requestImportPhotosForm->handleRequest($request);
        if ($requestImportPhotosForm->isSubmitted()) {
            $importPhotosService->doImport($user);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'importPhotosTokenFrom' => $importPhotosTokenFrom,
            'requestImportPhotosForm' => $requestImportPhotosForm,
        ]);
    }
}
