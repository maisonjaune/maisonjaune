<?php

namespace App\Twig;

use App\Service\Media\MediaManager;
use App\Service\Media\MediaPackageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MediaExtension extends AbstractExtension
{
    public function __construct(private MediaPackageInterface $mediaPackage)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('media_url', [$this->mediaPackage, 'getUrl']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('media_url', [$this->mediaPackage, 'getUrl']),
        ];
    }
}