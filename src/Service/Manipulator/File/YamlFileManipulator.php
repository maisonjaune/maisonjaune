<?php

namespace App\Service\Manipulator\File;

use App\Service\Manipulator\Manipulator;

class YamlFileManipulator extends Manipulator
{
    public function write(): bool
    {
        dd($this->data);
    }
}