<?php

namespace App\Service\Media\ImageManipulator;

class ImageFilterConfiguration
{
    private string $service;

    private array $arguments;

    private string $directory;

    public function __construct(private array $parameters)
    {
        $this->service = $parameters['service'];
        $this->arguments = $parameters['arguments'];
        $this->directory = $parameters['directory'];
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}