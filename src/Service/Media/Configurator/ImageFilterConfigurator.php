<?php

namespace App\Service\Media\Configurator;

use App\Service\Media\Exception\UndefinedProviderConfigurationException;
use App\Service\Media\ImageManipulator\ImageFilterConfiguration;

class ImageFilterConfigurator
{
    /**
     * @var ImageFilterConfiguration[]
     */
    private array $filters;

    public function __construct(array $parameters)
    {
        foreach ($parameters['filters'] as $key => $filter) {
            $this->filters[$key] = new ImageFilterConfiguration($filter);
        }
    }

    public function getConfiguration(string $filter): ImageFilterConfiguration
    {
        if (!isset($this->filters[$filter])) {
            throw new UndefinedProviderConfigurationException($filter);
        }

        return $this->filters[$filter];
    }
}