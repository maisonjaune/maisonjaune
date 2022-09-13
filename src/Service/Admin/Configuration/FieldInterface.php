<?php

namespace App\Service\Admin\Configuration;

interface FieldInterface
{
    public function getProperty(): string;

    public function getLabel(): string;

    public function getView(): ?string;
}