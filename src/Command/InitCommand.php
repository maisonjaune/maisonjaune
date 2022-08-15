<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class InitCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private KernelInterface $kernel;

    private ContainerBagInterface $parameterBag;

    protected static $defaultName = 'app:init';

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel, ContainerBagInterface $parameterBag)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
        $this->parameterBag = $parameterBag;
    }

    protected function configure()
    {
        $this
            ->setDescription('Initialisation de l\'application')
            ->addOption('group', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Execute fixture in this group');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '-1');
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);

        $io = new SymfonyStyle($input, $output);
        $io->section("Initialisation de l'application");

        $this->removeFiles($io, $output);
        $this->dropDatabase($io, $output);
        $this->createDatabase($io, $output);

        $this->executeMigration($io, $output);

        $this->executeFixture($io, $output, $input->getOption('group'));

        $end = microtime(true);

        $seconds = $end - $start;

        $minutes = floor($seconds / 60);
        $rest = floor($seconds - ($minutes * 60));

        $io->success("Temps d'exécution de la commande : " . $minutes . " minutes " . $rest . " secondes");

        return 0;
    }

    private function removeFiles(SymfonyStyle $io, OutputInterface $output)
    {
        if ($this->kernel->getEnvironment() !== 'test') {
            $folders = [];

            $fileSystem = new Filesystem();
            foreach ($folders as $folder) {
                $fileSystem->remove($folder);
                $fileSystem->mkdir($folder);
            }

            $io->success("Suppression des fichiers effectué");
        }
    }

    private function dropDatabase(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:database:drop');
        $argument = new ArrayInput([
            '--force' => true
        ]);
        $command->run($argument, $output);
        $io->success("Drop de la base de données effectué");
    }

    private function createDatabase(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:database:create');
        $argument = new ArrayInput([]);
        $command->run($argument, $output);
        $io->success("Création de la base de données effectué");
    }

    private function executeMigration(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:migrations:migrate');
        $argument = new ArrayInput([
            '--no-interaction' => true
        ]);
        $argument->setInteractive(false);
        $command->run($argument, $output);
        $io->success("Execution des migrations effectué");
    }

    private function executeFixture(SymfonyStyle $io, OutputInterface $output, array $groups = [])
    {
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $argument = new ArrayInput([
            '--append' => true,
            '--group' => array_merge(['initial'], [$this->kernel->getEnvironment()], $groups)
        ]);
        $command->run($argument, $output);
        $io->success("Ajout du jeu de test effectué");
    }
}