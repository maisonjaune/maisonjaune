<?php

namespace App\Service\Media\Exception;

use Exception;

class UndefinedImageFilterException extends Exception
{
    public function __construct(string $filterName)
    {
        parent::__construct(sprintf('Image filter "%s" is not defined.', $filterName));
    }
}