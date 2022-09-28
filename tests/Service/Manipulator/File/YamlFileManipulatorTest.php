<?php

namespace App\Tests\Service\Manipulator\File;

use App\Service\Manipulator\ManipulatorInterface;
use App\Tests\PhpUnit\Constraint\IsEqualIgnoreEndOfLine;
use PHPUnit\Framework\TestCase;
use App\Service\Manipulator\File\YamlFileManipulator;

class YamlFileManipulatorTest extends TestCase
{
    private ManipulatorInterface $manipulator;

    protected function setUp(): void
    {
        $this->manipulator = new YamlFileManipulator();
    }

    public function testSomething(): void
    {
        $data = <<<DATA
        parameters:
            firstname: 'Gwennael'
            lastname: 'Jean'
        
        services:
        
            App\Service\UserService:
                class: App\Service\UserService
                args: ['%firstname%', '%lastname%']
        
        other:
            test: 1
        DATA;

        $this->manipulator
            ->load($data)
            ->section('services')
                ->add('test_service_1')
                    ->setValue('class', 'App\Service\TestService')
                    ->setValue('tag', ['name' => 'service 1', 'i' => 5])
                ->end()
                ->add('test_service_2')
                    ->setValue('class', 'App\Service\TestService')
                    ->setValue('tag', ['name' => 'service 2', 'i' => 10])
                ->end()
            ->end()
            ->section('services')
                ->add('test_service_3')
                    ->setValue('class', 'App\Service\TestService')
                    ->setValue('tag', ['name' => 'service 3', 'i' => 15])
                ->end()
                ->add('test_service_4')
                    ->setValue('class', 'App\Service\TestService')
                    ->setValue('tag', ['name' => 'service 4', 'i' => 20])
                ->end()
            ->end()
            ->section('security')
                ->add('test_service_5')
                    ->setValue('class', 'App\Service\TestService')
                    ->setValue('tag', ['name' => 'service 5', 'i' => 25])
                ->end()
                ->add('test_service_6')
                    ->setValue('class', 'App\Service\TestService')
                    ->setValue('tag', ['name' => 'service 6', 'i' => 30])
                ->end()
            ->end()
        ;

        $this->assertThat($this->manipulator->get(), new IsEqualIgnoreEndOfLine(<<<EOT
        parameters:
            firstname: 'Gwennael'
            lastname: 'Jean'
        
        services:
        
            App\Service\UserService:
                class: App\Service\UserService
                args: ['%firstname%', '%lastname%']
            test_service_1:
                class: App\Service\TestService
                tag: { name: 'service 1', i: 5 }
            test_service_2:
                class: App\Service\TestService
                tag: { name: 'service 2', i: 10 }
            test_service_3:
                class: App\Service\TestService
                tag: { name: 'service 3', i: 15 }
            test_service_4:
                class: App\Service\TestService
                tag: { name: 'service 4', i: 20 }
        
        other:
            test: 1
        security:
            test_service_5:
                class: App\Service\TestService
                tag: { name: 'service 5', i: 25 }
            test_service_6:
                class: App\Service\TestService
                tag: { name: 'service 6', i: 30 }
        EOT));
    }
}
