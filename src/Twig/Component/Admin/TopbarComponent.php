<?php

namespace App\Twig\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('admin-topbar', template: '.components/admin/topbar.html.twig')]
class TopbarComponent
{
}