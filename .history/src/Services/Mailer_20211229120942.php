<?php
namespace App\Service;
use Symfony\Component\Mailer\MailerInterface;
class Mailer
{
    private $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
}