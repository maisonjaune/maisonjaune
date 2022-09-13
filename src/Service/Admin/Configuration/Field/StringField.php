<?php

namespace App\Service\Admin\Configuration\Field;

use App\Service\Admin\Configuration\Field;

class StringField extends Field
{
    const DEFAULT_VIEW = 'admin/CRUD/fields/string.html.twig';

    public function __construct(string $property, string $label, ?string $view = null)
    {
        parent::__construct($property, $label, $view ?? self::DEFAULT_VIEW);
    }
}