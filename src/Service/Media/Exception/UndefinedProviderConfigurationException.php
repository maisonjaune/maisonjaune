<?php

namespace App\Service\Media\Exception;

use App\Enum\Media\ProviderEnum;
use Exception;

class UndefinedProviderConfigurationException extends Exception
{
    public function __construct(ProviderEnum $provider)
    {
        parent::__construct(sprintf('The configuration for provider "%s" is not defined.', $provider->value));
    }
}