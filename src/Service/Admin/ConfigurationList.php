<?php

namespace App\Service\Admin;

use App\Service\Admin\Configuration\Field;

class ConfigurationList implements ConfigurationListInterface
{
    /**
     * @var Field[]
     */
    private array $fields = [];

    public function add(Field $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }
}