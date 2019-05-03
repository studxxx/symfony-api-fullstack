<?php

namespace App\Email;

use App\Entity\User;
use Twig\Environment;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'email/confirmation.html.twig',
            [
                'user' => $user
            ]
        );

        $message = (new \Swift_Message('Hello from API PLATFORM!'))
            ->setFrom('stud181177@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($body);

        $this->mailer->send($message);
    }
}
