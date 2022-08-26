<?php

namespace App\Maker\Admin\Provider;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

class ClassDetailProvider
{
    public function __construct(private Generator $generator)
    {
    }

    public function getFormClassDetail(ClassNameDetails $entityClassDetails): ClassNameDetails
    {
        $i = 0;

        do {
            $formClassDetails = $this->generator->createClassNameDetails(
                $entityClassDetails->getRelativeNameWithoutSuffix().($i ?: '').'Type',
                'Form\\Admin\\',
                'Type'
            );
            ++$i;
        } while (class_exists($formClassDetails->getFullName()));

        return $formClassDetails;
    }
}