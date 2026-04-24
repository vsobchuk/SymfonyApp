<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthTokenRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/{username}/{token}', name: 'auth_login')]
    public function login(string $username, string $token, Request $request, AuthTokenRepository $authTokenRepository): Response
    {
        $tokenData = $authTokenRepository->findOneByTokenAndUsername($token, $username);
        if (empty($tokenData)) {
            return new Response('User not found', 404);
        }

        $session = $request->getSession();
        $session->set('user_id', $tokenData->getUser()->getId());
        $session->set('username', $tokenData->getUser()->getName());

        $this->addFlash('success', 'Welcome back, ' . $username . '!');

        return $this->redirectToRoute('home');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();

        $this->addFlash('info', 'You have been logged out successfully.');

        return $this->redirectToRoute('home');
    }
}
