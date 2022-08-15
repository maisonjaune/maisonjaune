<?php

namespace App\DataFixtures\Node;

use App\DataFixtures\AbstractFixture;
use App\DataFixtures\UserFixture;
use App\Entity\Node\Page;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PageFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $key => $data) {
            $entity = (new Page())
                ->setTitle($data['title'])
                ->setContent($data['content']);

            $author = $this->getReference(UserFixture::class . $data['author']);

            if ($author instanceof User) {
                $entity->setAuthor($author);
            }

            if (isset($data['isActif'])) {
                $entity->setIsActif(!!$data['isActif']);
            }

            if (isset($data['publishedAt'])) {
                $entity->setPublishedAt($this->getDateTime($data['publishedAt']));
            }

            $this->addReference(self::class . $key, $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }

    protected function getYamlPath()
    {
        return 'node/pages.yaml';
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}