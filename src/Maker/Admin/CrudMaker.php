<?php

namespace App\Maker\Admin;

use App\Maker\Admin\Model\RepositoryData;
use App\Maker\Admin\Provider\ClassDetailProvider;
use App\Service\Admin\AdminCRUD;
use App\Service\Admin\ConfigurationListInterface;
use App\Service\Admin\CRUDController;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;

class CrudMaker extends AbstractMaker
{
    private ?string $adminClassName = null;

    private ?string $controllerClassName = null;

    private Inflector $inflector;

    public function __construct(
        private DoctrineHelper $doctrineHelper,
    )
    {
        $this->inflector = InflectorFactory::create()->build();
    }

    public static function getCommandName(): string
    {
        return 'app:make:admin:crud';
    }

    public static function getCommandDescription(): string
    {
        return 'Create Admin CRUD for Doctrine entity class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('entity', InputArgument::OPTIONAL, sprintf('The class name of the entity to create CRUD (e.g. <fg=yellow>%s</>)', Str::asClassName(Str::getRandomTerm())))
        ;

        $inputConfig->setArgumentAsNonInteractive('entity');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (null === $input->getArgument('entity')) {
            $argument = $command->getDefinition()->getArgument('entity');

            $entities = $this->doctrineHelper->getEntitiesForAutocomplete();

            $question = new Question($argument->getDescription());
            $question->setAutocompleterValues($entities);

            $value = $io->askQuestion($question);

            $input->setArgument('entity', $value);
        }

        // Ask Admin Class
        $defaultAdminClass = Str::asClassName(sprintf('%s Admin', $input->getArgument('entity')));

        $this->adminClassName = $io->ask(
            sprintf('Choose a name for your admin class (e.g. <fg=yellow>%s</>)', $defaultAdminClass),
            $defaultAdminClass
        );

        // Ask Controller Class
        $defaultControllerClass = Str::asClassName(sprintf('%s Controller', $input->getArgument('entity')));

        $this->controllerClassName = $io->ask(
            sprintf('Choose a name for your controller class (e.g. <fg=yellow>%s</>)', $defaultControllerClass),
            $defaultControllerClass
        );
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $inflector = InflectorFactory::create()->build();

        $entityClassDetails = $generator->createClassNameDetails(
            Validator::entityExists($input->getArgument('entity'), $this->doctrineHelper->getEntitiesForAutocomplete()),
            'Entity\\'
        );

        $classDetailProvider = new ClassDetailProvider($generator);

        $entityDoctrineDetails = $this->doctrineHelper->createDoctrineDetails($entityClassDetails->getFullName());

        if (null === $entityDoctrineDetails->getRepositoryClass()) {
            throw new RuntimeCommandException("The entity {$entityClassDetails->getFullName()} does not have a repository class.");
        }

        $repositoryData = new RepositoryData($generator, $entityDoctrineDetails);

        $adminClassDetails = $generator->createClassNameDetails(
            $this->adminClassName,
            'Admin\\',
            'Admin'
        );

        $controllerClassDetails = $generator->createClassNameDetails(
            $this->controllerClassName,
            'Controller\\Admin\\',
            'Controller'
        );

        $this->generateAdmin($generator, $adminClassDetails, $controllerClassDetails, $entityClassDetails);
        $this->generateController($generator, $controllerClassDetails, $adminClassDetails);

//        $routeName = Str::asRouteName($controllerClassDetails->getRelativeNameWithoutSuffix());
//        $templatesPath = Str::asFilePath($controllerClassDetails->getRelativeNameWithoutSuffix());
//
//        dd($repositoryData, $classDetailProvider->getFormClassDetail($entityClassDetails));

        $generator->writeChanges();
    }

    private function generateAdmin(Generator $generator, ClassNameDetails $classDetails, ClassNameDetails $controllerClassDetails, ClassNameDetails $entityClassDetails): void
    {
        $useStatements = new UseStatementGenerator([
            $controllerClassDetails->getFullName(),
            $entityClassDetails->getFullName(),
            AdminCRUD::class,
            ConfigurationListInterface::class
        ]);

        $generator->generateClass($classDetails->getFullName(), 'makers/admin/crud/admin/Admin.tpl.php', [
            'namespace' => Str::getNamespace($classDetails->getFullName()),
            'use_statements' => $useStatements,
            'entity_class_name' => $entityClassDetails->getShortName(),
            'controller_class_name' => $controllerClassDetails->getShortName(),
            'router_prefix' => '',
        ]);
    }

    private function generateController(Generator $generator, ClassNameDetails $classDetails, ClassNameDetails $adminClassDetails): void
    {
        $useStatements = new UseStatementGenerator([
            $adminClassDetails->getFullName(),
            CRUDController::class
        ]);

        $generator->generateController($classDetails->getFullName(), 'makers/admin/crud/controller/Controller.tpl.php', [
            'namespace' => Str::getNamespace($classDetails->getFullName()),
            'use_statements' => $useStatements,
            'admin_class_name' => $adminClassDetails->getShortName(),
        ]);
    }
}