<?php

namespace App\Service\Media\ImageManipulator;

class ImageFilterConfiguration
{
    private string $service;

    private array $arguments;

    public function __construct(private array $parameters)
    {
        $this->service = $parameters['service'];
        $this->arguments = $parameters['arguments'];
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}