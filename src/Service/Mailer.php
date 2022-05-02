<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer{
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;

    }

    public function sendEmail($email,$token)
    {
        $email = (new TemplatedEmail())
            ->from('fabien@example.com')
            ->to(new Address($email))
            ->subject("Merci de votre inscription sur le site de la nîmesalerie !")

            // path of the Twig template to render
            ->htmlTemplate('emails/signup.html.twig')

            // pass variables (name => value) to the template
            ->context([

                'token' => $token,

            ]);

        $this->mailer->send($email);
    }
}