<?php

namespace App\Maker\Admin;

use App\Maker\Admin\Manipulator\YamlSourceManipulator;
use App\Maker\Admin\Model\RepositoryData;
use App\Maker\Admin\Provider\ClassDetailProvider;
use App\Service\Admin\AdminCRUD;
use App\Service\Admin\ConfigurationListInterface;
use App\Service\Admin\CRUDController;
use App\Service\Admin\Utils\RoleNameGenerator;
use App\Service\Admin\Utils\RouteNameGenerator;
use App\Service\Manipulator\File\YamlFileManipulator;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Doctrine\DoctrineHelper;
use Symfony\Bundle\MakerBundle\Doctrine\EntityDetails;
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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use App\Maker\Admin\Generator\ConfigurationListGenerator;

class CrudMaker extends AbstractMaker
{
    private Inflector $inflector;

    private Filesystem $filesystem;

    public function __construct(
        private DoctrineHelper         $doctrineHelper,
        private EntityManagerInterface $entityManager
    )
    {
        $this->inflector = InflectorFactory::create()->build();
        $this->filesystem = new Filesystem();
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
            ->addOption('admin', 'a', InputOption::VALUE_OPTIONAL, 'The admin class basename')
            ->addOption('controller', 'c', InputOption::VALUE_OPTIONAL, 'The controller class basename')
            ->addOption('service', 's', InputOption::VALUE_OPTIONAL, 'The services YAML file');

        $inputConfig->setArgumentAsNonInteractive('entity');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        // TODO: Implement configureDependencies() method.
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $io->title('Welcome to the Admin CRUD Maker');

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

        $input->setOption('admin', $io->ask(
            sprintf('Choose a name for your admin class (e.g. <fg=yellow>%s</>)', $defaultAdminClass),
            $defaultAdminClass
        ));

        // Ask Controller Class
        $defaultControllerClass = Str::asClassName(sprintf('%s Controller', $input->getArgument('entity')));

        $input->setOption('controller', $io->ask(
            sprintf('Choose a name for your controller class (e.g. <fg=yellow>%s</>)', $defaultControllerClass),
            $defaultControllerClass
        ));

        $input->setOption('service', $io->ask(
            'What is your services YAML configuration file',
            'services.yaml'
        ));
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $inflector = InflectorFactory::create()->build();

        $input->setOption('service', sprintf('config/%s', $input->getOption('service')));

        if (!$this->filesystem->exists($input->getOption('service'))) {
            throw new RuntimeCommandException(sprintf('The file "%s" does not exist.', $input->getOption('service')));
        }

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
            $input->getOption('admin'),
            'Admin\\',
            'Admin'
        );

        $controllerClassDetails = $generator->createClassNameDetails(
            $input->getOption('controller'),
            'Controller\\Admin\\',
            'Controller'
        );

        $routePrefix = Str::asSnakeCase($controllerClassDetails->getRelativeNameWithoutSuffix());

        $this->generateAdmin($generator, $adminClassDetails, $controllerClassDetails, $entityClassDetails, $routePrefix);
        $this->generateController($generator, $controllerClassDetails, $adminClassDetails);
        $this->updateConfig($input->getOption('service'), $io, $adminClassDetails);

//        $routeName = Str::asRouteName($controllerClassDetails->getRelativeNameWithoutSuffix());
//        $templatesPath = Str::asFilePath($controllerClassDetails->getRelativeNameWithoutSuffix());

//        dd($repositoryData, $classDetailProvider->getFormClassDetail($entityClassDetails));

//        IMPORTANT : Penser à indiquer l'ajout des roles aux utilisateurs
//        IMPORTANT : Penser à indiquer l'ajout du liens

        $generator->writeChanges();

        $io->newLine();
        $io->section("Next: When you're ready :");

        $io->listing([
            "<info>Update</info> roles in your security configuration (maybe in <info>config/packages/security.yaml</info> (in <info>role_hierarchy</info> section)) :",
            "<info>Manage</info> your route in your application"
        ]);

        $io->table(
            ["Type", "Role", "Route"],
            [
                ["index", RoleNameGenerator::generate($routePrefix, 'index'), RouteNameGenerator::generate($routePrefix, 'index')],
                ["new", RoleNameGenerator::generate($routePrefix, 'new'), RouteNameGenerator::generate($routePrefix, 'new')],
                ["show", RoleNameGenerator::generate($routePrefix, 'show'), RouteNameGenerator::generate($routePrefix, 'show')],
                ["edit", RoleNameGenerator::generate($routePrefix, 'edit'), RouteNameGenerator::generate($routePrefix, 'edit')],
                ["delete", RoleNameGenerator::generate($routePrefix, 'delete'), RouteNameGenerator::generate($routePrefix, 'delete')],
            ]
        );
    }

    private function generateAdmin(
        Generator        $generator,
        ClassNameDetails $classDetails,
        ClassNameDetails $controllerClassDetails,
        ClassNameDetails $entityClassDetails,
        string           $routePrefix): void
    {
        $useStatements = new UseStatementGenerator([
            $controllerClassDetails->getFullName(),
            $entityClassDetails->getFullName(),
            AdminCRUD::class,
            ConfigurationListInterface::class
        ]);

        $configuratorFieldGenerator = new ConfigurationListGenerator($this->entityManager, $entityClassDetails, $useStatements);

        $generator->generateClass($classDetails->getFullName(), 'makers/admin/crud/admin/Admin.tpl.php', [
            'namespace' => Str::getNamespace($classDetails->getFullName()),
            'use_statements' => $useStatements,
            'configurator_field' => $configuratorFieldGenerator,
            'entity_class_name' => $entityClassDetails->getShortName(),
            'controller_class_name' => $controllerClassDetails->getShortName(),
            'router_prefix' => $routePrefix,
        ]);
    }

    private function generateController(
        Generator        $generator,
        ClassNameDetails $classDetails,
        ClassNameDetails $adminClassDetails
    ): void
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

    private function updateConfig(string $path, ConsoleStyle $io, ClassNameDetails $classDetails)
    {
        $manipulator = $this->createYamlManipulator($path);

        $manipulator
            ->section('services')
            ->add($classDetails->getFullName())
            ->setValue('tags', ["name" => "admin.crud"])
            ->end()
            ->end();

        $this->filesystem->dumpFile($path, $manipulator->get());

        $comment = $this->filesystem->exists($path) ?
            '<fg=yellow>updated</>'
            : '<fg=blue>created</>';

        $io->comment(sprintf('%s: %s', $comment, $path));
    }

    private function createYamlManipulator(string $path): YamlFileManipulator
    {
        return (new YamlFileManipulator())
            ->load(file_get_contents($path));
    }
}