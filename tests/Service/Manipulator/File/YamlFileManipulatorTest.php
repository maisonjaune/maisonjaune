<?php

namespace App\Tests\Service\Manipulator\File;

use App\Service\Manipulator\ManipulatorInterface;
use PHPUnit\Framework\TestCase;
use App\Service\Manipulator\File\YamlFileManipulator;

class YamlFileManipulatorTest extends TestCase
{
    private ManipulatorInterface $manipulator;

    private $data = <<<EOT
    parameters:
        firstname: 'Gwennael'
        lastname: 'Jean'
    
    services:
        App\Service\UserService:
            class: App\Service\UserService
            args: ['%firstname%', '%lastname%']
    
    other:
        test: 1
    EOT;

    protected function setUp(): void
    {
        $this->manipulator = new YamlFileManipulator();
    }

    public function testSomething(): void
    {
        $this->manipulator->load($this->data);

        $this->assertTrue(true);
    }
}
