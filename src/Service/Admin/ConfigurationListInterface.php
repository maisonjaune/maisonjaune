<?php

namespace App\Service\Admin;

use App\Service\Admin\Configuration\Field;

interface ConfigurationListInterface
{
    public function add(Field $field): self;

    public function getFields(): array;

    public function getSearchableFields(): array;
}