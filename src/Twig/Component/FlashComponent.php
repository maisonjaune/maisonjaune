<?php

namespace App\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('flash', template: '.components/flash.html.twig')]
class FlashComponent
{
}