<?php

namespace App\Service\Admin\Template;

interface TemplateRegistryInterface
{
    public function getTemplate(string $name): Template;
}