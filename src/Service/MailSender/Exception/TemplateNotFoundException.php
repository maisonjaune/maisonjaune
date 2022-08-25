<?php

namespace App\Service\MailSender\Exception;

use Throwable;

class TemplateNotFoundException extends \Exception
{
    public function __construct($template, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("Template \"%s\" not found", $template), $code, $previous);
    }
}