<?php

namespace App\Service\Media;

use Exception;

class UndefinedMediaProviderException extends Exception
{
    public function __construct(string $providerName)
    {
        parent::__construct(sprintf('Media provider "%s" is not defined.', $providerName));
    }
}