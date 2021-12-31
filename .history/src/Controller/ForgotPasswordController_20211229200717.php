<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/forgot-password', name: 'forgot_password',  methods: ['GET', 'POST'])]
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

    #[Route('/forgot-password/{id<\d+>}/{token}', name: 'retrieve_credentials',  methods: ['GET'])]
    public function retrieveCredentialsFromTheURL(
        String $token,
        User $user,
    ): RedirectResponse {
        $this->session->set('Reset-Password-Token-URL', $token);

        $this->session->set('Reset-Password-Username', $user->getUserName());

        return $this->redirectToRoute('reset_password');
    }

    #[Route('/reset-password/', name: 'reset_password',  methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        UserPasswordHasherInterface $hasher
    ) {
        [
            'token' => $token,
            'username' => $username
        ] = $this->getCredentialsFromSession();

        $user = $this->userRepository->findOneBy(
            [
                'username' => $username
            ]
        );

        if (!$user) {
            return $this->redirectToRoute('forgot_password');
        }

        /** @var \DateTimeImmutable $forgotPasswordTokenMustBeVerifiedBefore */
        $forgotPasswordTokenBeVerifiedBefore = $user->getForgotPasswordTokenMustBeVerifiedBefore();

        if (($user->getForgotPasswordToken() === null)
            || ($user->getForgotPasswordToken() !== $token)
            || ($this->isNotRequestedInTime($forgotPasswordTokenBeVerifiedBefore))
        ) {
            return $this->redirectToRoute('forgot_password');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
    }

    private function getCredentialsFromSession(): array
    {
        return [
            'token' => $this->session->get('Reset-Password-Token-URL'),
            'username' => $this->session->get('Reset-Password-Username')
        ];
    }

    private function isNotRequestedInTime(\DateTimeImmutable $forgotPasswordTokenBeVerifiedBefore): bool
    {
        return (new \DateTimeImmutable('now') > $forgotPasswordTokenBeVerifiedBefore)
    }
}
