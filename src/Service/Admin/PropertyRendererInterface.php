<?php

namespace App\Service\Admin;

use App\Service\Admin\Configuration\Field;

interface PropertyRendererInterface
{
    public function render(object $entity, Field $property): mixed;
}