<?php

namespace App\Maker\Admin\Model;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Bundle\MakerBundle\Doctrine\EntityDetails;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

class RepositoryData
{
    private ClassNameDetails $repositoryClassDetails;

    public function __construct(Generator $generator, EntityDetails $entityDoctrineDetails)
    {
        $this->repositoryClassDetails = $generator->createClassNameDetails(
            '\\'.$entityDoctrineDetails->getRepositoryClass(),
            'Repository\\',
            'Repository'
        );
    }

    /**
     * @return string
     */
    public function getRepositoryFullClassName(): string
    {
        return $this->repositoryClassDetails->getFullName();
    }

    /**
     * @return string
     */
    public function getRepositoryShortClassName(): string
    {
        return $this->repositoryClassDetails->getShortName();
    }

    /**
     * @return string
     */
    public function getEntityVariableName(Inflector $inflector): string
    {
        return lcfirst($inflector->singularize($this->getRepositoryShortClassName()));
    }
}