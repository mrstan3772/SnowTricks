<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot/password', name: 'forgot_password')]
    public function index(): Response
    {
        private EntityManagerInterface $entityManager;

        private SessionInterface $session;
        
        return $this->render('forgot_password/index.html.twig', [
            'controller_name' => 'ForgotPasswordController',
        ]);
    }
}
