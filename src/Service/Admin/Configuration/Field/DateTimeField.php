<?php

namespace App\Service\Admin\Configuration\Field;

use App\Service\Admin\Configuration\Field;

class DateTimeField extends Field
{
    const DEFAULT_VIEW = 'admin/CRUD/fields/datetime.html.twig';

    public function __construct(string $property, string $label, string $format = 'd/m/Y H:i:s', ?string $view = null)
    {
        parent::__construct($property, $label, $view ?? self::DEFAULT_VIEW, [
            'format' => $format,
        ]);
    }
}