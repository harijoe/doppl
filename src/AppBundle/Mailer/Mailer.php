<?php

namespace AppBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class Mailer
{
    private $translator;
    private $autoreplyFromEmail;
    private $notificationFromEmail;
    private $notificationToEmail;
    private $templating;
    private $mailer;

    /**
     * @param Translator $translator
     * @param string $autoreplyFromEmail
     * @param string $notificationFromEmail
     * @param string $notificationToEmail
     * @param EngineInterface $templating
     * @param \Swift_Mailer $mailer
     */
    public function __construct(
        Translator $translator,
        $autoreplyFromEmail,
        $notificationFromEmail,
        $notificationToEmail,
        EngineInterface $templating,
        \Swift_Mailer $mailer
    ) {
        $this->translator = $translator;
        $this->autoreplyFromEmail = $autoreplyFromEmail;
        $this->notificationFromEmail = $notificationFromEmail;
        $this->notificationToEmail = $notificationToEmail;
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    public function sendNotification($name, $message, $email)
    {
        $body = $this->templating->render(
            ':emails/contact:notification.html.twig',
            ['name' => $name,
            'message' => $message,
            'email' => $email,
            'locale' => $this->translator->getLocale()]
        );
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('email.notification.subject'))
            ->setFrom($this->notificationFromEmail)
            ->setTo($this->notificationToEmail)
            ->setBody($body)
        ;

        $this->mailer->send($message);
    }


    public function sendAutoreply($name, $email)
    {
        $body = $this->templating->render(
            ':emails/contact:autoreply.html.twig',
            ['name' => $name]
        );
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('email.autoreply.subject'))
            ->setFrom($this->autoreplyFromEmail)
            ->setTo($email)
            ->setBody($body)
        ;

        $this->mailer->send($message);
    }
}
