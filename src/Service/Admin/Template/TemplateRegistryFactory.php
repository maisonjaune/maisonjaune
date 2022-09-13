<?php

namespace App\Service\Admin\Template;

class TemplateRegistryFactory
{
    public function create(string $type): TemplateRegistryInterface
    {
        return (new TemplateRegistry())
            ->setTemplateIndex(new Template('admin/CRUD/index.html.twig', ucfirst(strtolower($type)) . ' List'))
            ->setTemplateNew(new Template('admin/CRUD/new.html.twig', ucfirst(strtolower($type)) . ' New'))
            ->setTemplateShow(new Template('admin/CRUD/show.html.twig', ucfirst(strtolower($type)) . ' Show'))
            ->setTemplateEdit(new Template('admin/CRUD/edit.html.twig', ucfirst(strtolower($type)) . ' Edit'))
            ->setTemplateDelete(new Template('admin/CRUD/delete.html.twig', ucfirst(strtolower($type)) . ' Delete'));
    }
}