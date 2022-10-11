<?php

namespace App\Service\Admin\Template;

class TemplateRegistryFactory
{
    public function create(string $type, string $title): TemplateRegistryInterface
    {
        return (new TemplateRegistry())
            ->setTemplateIndex(new Template('admin/CRUD/index.html.twig', $title . ' List'))
            ->setTemplateNew(new Template('admin/CRUD/new.html.twig', $title . ' New'))
            ->setTemplateShow(new Template('admin/CRUD/show.html.twig', $title . ' Show'))
            ->setTemplateEdit(new Template('admin/CRUD/edit.html.twig', $title . ' Edit'))
            ->setTemplateDelete(new Template('admin/CRUD/delete.html.twig', $title . ' Delete'));
    }
}