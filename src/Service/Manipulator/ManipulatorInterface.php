<?php

namespace App\Service\Manipulator;

interface ManipulatorInterface
{
    public function load(string $data): self;

    public function write(): bool;
}