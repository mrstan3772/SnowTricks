<?php

namespace App\Controller;

use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
        Mailer $mailer,
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

            $user->setForgotPasswordToken($tokenGenerator->generateToken())
                ->setForgotPasswordTokenRequestAt(new \DateTimeImmutable('now'))
                ->setForgotPasswordTokenMustBeVerifiedBefore(new \DateTimeImmutable('+15 minutes'));

            $this->entityManager->flush();

            $mailer->sendForgotPasswordSignatureMessage($user);

            $this->addFlash('success', 'un email vous a été envoyé pour redéfinir votre mot de passe.');

            return $this->redirectToRoute('security_login');
        }

        return $this->render(
            'forgot_password/forgot_password_step_1.html.twig',
            [
                'forgotPasswordFormStep1' => $form->createView(),
            ]
        );
    }

    #[Route('/reset-password/', name: 'reset_password',  methods: ['GET', 'POST'])]
    public function retrieveCredentialsFromTheURL(
        String $token,
        User $user,
    ): RedirectResponse {
        [
            'token' => $token,
            'username' => $username
        ] = $this->getCredentialsFromSession();
    }

    private function getCredentialsFromSession(): Array 
    {
        return [
            'token' => $this->session->get('Reset-Password-Token-URL')
            'userEmail',
        ]
    }
}
