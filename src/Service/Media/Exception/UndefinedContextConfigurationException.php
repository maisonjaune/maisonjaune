<?php

namespace App\Service\Media\Exception;

use App\Enum\Media\ContextEnum;
use Exception;

class UndefinedContextConfigurationException extends Exception
{
    public function __construct(ContextEnum $context)
    {
        parent::__construct(sprintf('The configuration for context "%s" is not defined.', $context->value));
    }
}