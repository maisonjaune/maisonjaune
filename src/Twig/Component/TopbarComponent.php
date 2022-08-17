<?php

namespace App\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('topbar', template: '.components/topbar.html.twig')]
class TopbarComponent
{
}