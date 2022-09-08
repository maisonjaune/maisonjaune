<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\UndefinedTemplateException;

class TemplateRegistry implements TemplateRegistryInterface
{
    private $templates = array();

    public function hasTemplate(string $name): bool
    {
        return isset($this->templates[$name]);
    }

    public function getTemplate(string $name): string
    {
        if (!$this->hasTemplate($name)) {
            throw new UndefinedTemplateException($name);
        }

        return $this->templates[$name];
    }

    public function setTemplateIndex(string $view): self
    {
        $this->setTemplate('index', $view);

        return $this;
    }

    public function setTemplateNew(string $view): self
    {
        $this->setTemplate('new', $view);

        return $this;
    }

    public function setTemplateShow(string $view): self
    {
        $this->setTemplate('show', $view);

        return $this;
    }

    public function setTemplateEdit(string $view): self
    {
        $this->setTemplate('edit', $view);

        return $this;
    }

    public function setTemplateDelete(string $view): self
    {
        $this->setTemplate('delete', $view);

        return $this;
    }

    public function setTemplate(string $name, string $view): self
    {
        $this->templates[$name] = $view;

        return $this;
    }
}