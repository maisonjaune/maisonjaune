<?php

namespace App\Service\Media\Configurator;

use App\Enum\Media\ContextEnum;
use App\Enum\Media\ProviderEnum;
use App\Service\Media\Exception\UndefinedContextConfigurationException;
use App\Service\Media\Exception\UndefinedProviderConfigurationException;

class MediaConfigurator
{
    private string $directory;

    private string $publicDirectory;

    /**
     * @var ContextConfigurator[]
     */
    private array $contexts;

    /**
     * @var ProviderConfigurator[]
     */
    private array $providers;

    public function __construct(array $parameters)
    {
        $this->directory = $parameters['directory'];
        $this->publicDirectory = $parameters['public_directory'];

        foreach ($parameters['contexts'] as $key => $contextParameters) {
            $this->contexts[$key] = new ContextConfigurator($contextParameters);
        }

        foreach ($parameters['providers'] as $key => $providerParameters) {
            $this->providers[$key] = new ProviderConfigurator($providerParameters);
        }
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getPublicDirectory(): string
    {
        return $this->publicDirectory;
    }

    public function getFullDirectory(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            rtrim($this->getDirectory()),
            ltrim($this->getPublicDirectory())
        ]);
    }

    public function getContextConfigurator(ContextEnum $context): ContextConfigurator
    {
        if (!isset($this->contexts[$context->value])) {
            throw new UndefinedContextConfigurationException($context);
        }

        return $this->contexts[$context->value];
    }

    public function getProviderConfigurator(ProviderEnum $provider): ProviderConfigurator
    {
        if (!isset($this->providers[$provider->value])) {
            throw new UndefinedProviderConfigurationException($provider);
        }

        return $this->providers[$provider->value];
    }
}