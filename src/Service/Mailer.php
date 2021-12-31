<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class Mailer
{
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendForgotPasswordSignatureMessage(User $user)
    {
        $email = (new TemplatedEmail())
            ->from(new Address('snowtricks@dhoruba.com', 'Snow Tricks'))
            ->to(new Address($user->getEmail(), $user->getUserName()))
            ->subject('Modification de votre mot de passe')
            ->htmlTemplate('forgot_password/forgot_password_email.html.twig')
            ->context(
                [
                    'user' => $user,
                ]
            );
            
        $this->mailer->send($email);
    }
}
