<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class InitCommand extends Command
{
    protected static $defaultName = 'app:init';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private KernelInterface        $kernel,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Initialisation de l\'application');
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

        $this->executeFixture($io, $output);

        $end = microtime(true);

        $seconds = $end - $start;

        $minutes = floor($seconds / 60);
        $rest = floor($seconds - ($minutes * 60));

        $io->success("Temps d'exécution de la commande : " . $minutes . " minutes " . $rest . " secondes");

        return Command::SUCCESS;
    }

    private function removeFiles(SymfonyStyle $io, OutputInterface $output)
    {
        if ($this->kernel->getEnvironment() !== 'test') {
            $folders = [
                $this->kernel->getProjectDir() . '/public/media',
            ];

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

    private function executeFixture(SymfonyStyle $io, OutputInterface $output)
    {
        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $argument = new ArrayInput([
            '--append' => true,
        ]);
        $command->run($argument, $output);
        $io->success("Ajout du jeu de test effectué");
    }
}