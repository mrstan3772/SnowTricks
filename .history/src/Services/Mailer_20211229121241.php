<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
            ->from(new NamedAddress('alienmailcarrier@example.com', 'The Space Bar'))
            ->to(new NamedAddress($user->getEmail(), $user->getFirstName()))
            ->subject('Welcome to the Space Bar!')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
                // You can pass whatever data you want
                //'user' => $user,
            ]);
        $this->mailer->send($email);
    }
}
