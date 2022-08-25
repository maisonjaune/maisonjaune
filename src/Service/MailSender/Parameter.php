<?php

namespace App\Service\MailSender;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Parameter
{
    private Address|string|null $sender = null;

    private array $recipient = [];

    private int $priority = Email::PRIORITY_NORMAL;

    private array $parameters = [];

    public function getSender(): Address|string|null
    {
        return $this->sender;
    }

    public function setSender(Address|string|null $sender): Parameter
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): array
    {
        return $this->recipient;
    }

    public function addRecipient(string|Address $recipient): Parameter
    {
        $this->recipient[] = $recipient;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): Parameter
    {
        $this->priority = $priority;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): Parameter
    {
        $this->parameters = $parameters;

        return $this;
    }
}