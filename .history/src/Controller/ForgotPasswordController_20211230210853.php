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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $requestStack,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }

    #[Route('/forgot-password', name: 'forgot_password',  methods: ['GET', 'POST'])]
    public function sendRecoveryLink(
        Request $request,
        Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response {

        if(! $this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
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
        $session = $this->requestStack->getSession();

        $session->set('Reset-Password-Token-URL', $token);

        $session->set('Reset-Password-Username', $user->getUserName());

        return $this->redirectToRoute('reset_password');
    }

    #[Route('/reset-password/', name: 'reset_password',  methods: ['GET', 'POST'])]
    public function resetPassword(
        Request $request,
        UserPasswordHasherInterface $hasher
    ): Response {
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

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('plainPassword')->getData()))
                ->setForgotPasswordToken(null)
                ->setForgotPasswordTokenVerifiedAt(new \DateTimeImmutable('now'));

            $this->entityManager->flush();

            $this->removeCredentialsFromSession();

            $this->addFlash('success', 'Votre mot de passe a été modifié, vous pouvez à présent vous connecter.');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('forgot_password/forgot_password_step_2.html.twig', [
            'forgotPasswordFormStep2' => $form->createView(),
            'passwordMustBeModifiedBefore' => $this->passwordMustBeModifiedBefore($user)
        ]);
    }

    /**
     * Gets the user ID and token from  the session. 
     *
     * @return array
     */
    private function getCredentialsFromSession(): array
    {
        $session = $this->requestStack->getSession();

        return [
            'token' => $session->get('Reset-Password-Token-URL'),
            'username' => $session->get('Reset-Password-Username')
        ];
    }

    /**
     * Validates or not the fact that the link was clicked in the allowed time
     *
     * @param \DateTimeImmutable $forgotPasswordTokenBeVerifiedBefore
     * @return boolean
     */
    private function isNotRequestedInTime(\DateTimeImmutable $forgotPasswordTokenBeVerifiedBefore): bool
    {
        return (new \DateTimeImmutable('now') > $forgotPasswordTokenBeVerifiedBefore);
    }

    /**
     * Removes the user ID and token from the session.
     *
     * @return Void
     */
    private function removeCredentialsFromSession(): Void
    {
        $session = $this->requestStack->getSession();

        $session->remove('Reset-Password-Token-URL');
        $session->remove('Reset-Password-Username');
    }

    /**
     * Return the time before which the password must be changed.
     *
     * @param User $user
     * @return String The timle in this format: 12h0
     */
    private function passwordMustBeModifiedBefore(User $user): String
    {
        /** @var \DateTimeImmutable $forgotPasswordTokenMustBeVerifiedBefore */
        $passwordMustBeModifiedBefore = $user->getForgotPasswordTokenMustBeVerifiedBefore();

        return $passwordMustBeModifiedBefore->format('H\hi');
    }
}
