<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Enum\Media\ContextEnum;
use App\Enum\Media\ProviderEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use App\Kernel;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of AbstractFixture
 *
 * @author gjean
 */
abstract class AbstractFixture extends Fixture implements ContainerAwareInterface, FixtureGroupInterface
{
    protected ?ContainerInterface $container = null;

    protected Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    protected function getData()
    {
        return Yaml::parse(file_get_contents($this->getAssetsPath() . '/data/' . trim($this->getYamlPath())));
    }

    protected function getDateTime($timestamp): \DateTimeImmutable
    {
        $date = new \DateTimeImmutable();

        if (preg_match('/^now:(.*)/', $timestamp, $match)) {
            $date = $date->modify($match[1]);
        } else {
            $date->setTimestamp($timestamp);
        }

        return $date;
    }

    protected function createFile($path): File
    {
        return new File($this->getAssetsPath() . '/media/' . ltrim($path, '/'));
    }

    protected function createMedia($path, $providerName = ProviderEnum::FILE, $context = ContextEnum::DEFAULT): Media
    {
        return (new Media())
            ->setBinaryContent($this->createFile($path))
            ->setProviderName($providerName)
            ->setContext($context);
    }

    protected function getRandomFileInDirectory($directory): File
    {
        $directory = $this->getAssetsPath() . '/media/random/' . ltrim($directory, '/');

        $finder = new Finder();
        $finder->files()->in($directory);

        if ($finder->count() === 0) {
            throw new \Exception('No random file exist in ' . $directory);
        }

        $rand = rand(0, $finder->count() - 1);

        $i = 0;

        $path = null;
        foreach ($finder as $key => $file) {
            if ($rand === $i) {
                $path = $file->getPathname();
            }

            $i++;
        }

        return new File($path);
    }

    /**
     * Sets the container.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return Kernel
     */
    public function getKernel()
    {
        return $this->container->get('kernel');
    }

    /**
     * @return string
     */
    public function getTempDir(): string
    {
        $path = $this->getKernel()->getProjectDir() . '/var/temp';

        if (!$this->filesystem->exists($path)) {
            $this->filesystem->mkdir($path, 0700);
        }

        return $path;
    }

    protected function getEnvironment(): string
    {
        return $this->getKernel()->getEnvironment();
    }

    protected function getAssetsPath()
    {
        return $this->container->getParameter('kernel.project_dir') . '/assets/fixtures';
    }

    public static function getGroups(): array
    {
        return ['initial'];
    }

    protected abstract function getYamlPath();
}
