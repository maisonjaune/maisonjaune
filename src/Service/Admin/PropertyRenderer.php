<?php

namespace App\Service\Admin;

use App\Service\Admin\Configuration\Field;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Environment;

class PropertyRenderer implements PropertyRendererInterface
{
    private PropertyAccessor $propertyAccess;

    public function __construct(private Environment $twig)
    {
        $this->propertyAccess = PropertyAccess::createPropertyAccessor();
    }

    public function render(object $entity, Field $field): mixed
    {
        return $this->twig->render($field->getView(), [
            'entity' => $entity,
            'value' => $this->propertyAccess->getValue($entity, $field->getProperty()),
            'field' => $field,
        ]);
    }
}