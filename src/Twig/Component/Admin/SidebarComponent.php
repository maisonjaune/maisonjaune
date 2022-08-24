<?php

namespace App\Twig\Component\Admin;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('admin-sidebar', template: '.components/admin/sidebar.html.twig')]
class SidebarComponent
{
}