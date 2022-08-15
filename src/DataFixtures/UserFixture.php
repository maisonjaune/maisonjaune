<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\CivilityEnum;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $key => $data) {
            $entity = (new User())
                ->setFirstname($data['firstname'])
                ->setLastname($data['lastname'])
                ->setEmail($data['email'])
                ->setPlainPassword($data['password'])
                ->setCivility(CivilityEnum::tryFrom($data['civility']))
                ->setIsMember($data['isMember']);

            if (isset($data['actif'])) {
                $entity->setIsActif(!!$data['actif']);
            }

            $this->addReference(self::class . $key, $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }

    protected function getYamlPath()
    {
        return 'users.yaml';
    }
}