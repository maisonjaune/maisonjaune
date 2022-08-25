<?php

namespace App\Service\MailSender;

use App\Service\MailSender\Exception\TemplateNotFoundException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailSender implements MailSenderInterface
{
    public function __construct(
        private Environment     $environment,
        private MailerInterface $mailer,
    )
    {
    }

    public function send(string $subject, string $templatePath, ?Parameter $parameters = null): void
    {
        $templateHtml = sprintf('@email/%s.html.twig', $templatePath);
        $templateTxt = sprintf('@email/%s.txt.twig', $templatePath);

        if (!$this->environment->getLoader()->exists($templateHtml)) {
            throw new TemplateNotFoundException($templateHtml);
        }

        $email = new Email();

        $email
            ->subject($subject)
            ->to(...$parameters->getRecipient())
            ->priority($parameters->getPriority())
            ->text('Sending emails is fun again!')
            ->html($this->environment->render($templateHtml, $parameters->getParameters()));

        if (null !== $parameters->getSender()) {
            $email->from($parameters->getSender());
        }

        if ($this->environment->getLoader()->exists($templateTxt)) {
            $email->text($this->environment->render($templateTxt, $parameters->getParameters()));
        }

        $this->mailer->send($email);
    }
}