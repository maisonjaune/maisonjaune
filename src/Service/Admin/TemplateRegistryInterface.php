<?php

namespace App\Service\Admin;

interface TemplateRegistryInterface
{
    public function getTemplate(string $name): string;
}