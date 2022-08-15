<?php

namespace App\DataFixtures\Node;

use App\DataFixtures\AbstractFixture;
use App\DataFixtures\UserFixture;
use App\Entity\Node\Brief;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BriefFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $key => $data) {
            $start = 0;
            $length = 0;

            $replace = $key;
            if (preg_match('/\{(\d*)\.\.(\d*)\}/', $key, $match)) {
                $replace = $match[0];
                $start = $match[1];
                $length = $match[2];
            }

            for ($i = $start; $i <= $length; $i++) {
                $entity = (new Brief())
                    ->setTitle(str_replace($replace, $i, $data['title']))
                    ->setContent($data['content']);

                $author = $this->getReference(UserFixture::class . $data['author']);

                if ($author instanceof User) {
                    $entity->setAuthor($author);
                }

                if (isset($data['isActif'])) {
                    $entity->setIsActif(!!$data['isActif']);
                }

                if (isset($data['isSticky'])) {
                    $entity->setIsSticky(!!$data['isSticky']);
                }

                if (isset($data['isDraft'])) {
                    $entity->setIsDraft(!!$data['isDraft']);
                }

                if (isset($data['publishedAt'])) {
                    $entity->setPublishedAt($this->getDateTime($data['publishedAt']));
                }

                $this->addReference(self::class . str_replace($replace, $i, $key), $entity);

                $manager->persist($entity);
            }
        }

        $manager->flush();
    }

    protected function getYamlPath()
    {
        return 'node/briefs.yaml';
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}