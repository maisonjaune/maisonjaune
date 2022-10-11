<?php

namespace Tournikoti\CrudBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Tournikoti\CrudBundle\DependencyInjection\TournikotiCrudExtension;

class TournikotiCrudBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TournikotiCrudExtension();
    }
}