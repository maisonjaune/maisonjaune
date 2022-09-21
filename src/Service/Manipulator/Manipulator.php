<?php

namespace App\Service\Manipulator;

abstract class Manipulator implements ManipulatorInterface
{
    protected string $data;

    public function load(string $data): ManipulatorInterface
    {
        $this->data = $data;
    }
}