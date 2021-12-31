<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private SessionInterface $session;

    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    #[Route('/forgot/password', name: 'forgot_password',  methods: ['GET', 'POST'])]
    public function sendRecoveryLink(
        Request $request,
        SendEmailMessage $sendEmailMessage,
        TokenGeneratorInterface $tokenGenerator
    ): Response {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy(
                [
                    'username' => $form['username']->getData()
                ]
            );

            /* Lure  */
            if (!$user) {
                $this->addFlash('success', 'un email vous a été envoyé pour redéfinir votre mot de passe.');

                return $this->redirectToRoute('security_login');
            }

            $user->setForgotPasswordToken($)
        }

        // return $this->render('forgot_password/index.html.twig', [
        //     'controller_name' => 'ForgotPasswordController',
        // ]);
    }
}
