<?php

namespace App\Admin;

use App\Controller\Admin\UserController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\Admin\AdminCRUD;
use App\Service\Admin\Configuration\Field\ArrayField;
use App\Service\Admin\Configuration\Field\BooleanField;
use App\Service\Admin\Configuration\Field\DateTimeField;
use App\Service\Admin\Configuration\Field\StringField;
use App\Service\Admin\ConfigurationListInterface;

class UserAdmin extends AdminCRUD
{
    public function configurationList(ConfigurationListInterface $configurationList): void
    {
        $configurationList
            ->add(new StringField('id', 'ID'))
            ->add(new StringField('firstname', 'Firstname'))
            ->add(new StringField('lastname', 'Lastname'))
            ->add(new StringField('email', 'Email'))
            ->add(new BooleanField('isActif', 'IsActif'))
            ->add(new BooleanField('isMember', 'IsMember'))
            ->add(new ArrayField('roles', 'Roles'))
            ->add(new DateTimeField('createdAt', 'CreatedAt'));
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getControllerClass(): string
    {
        return UserController::class;
    }

    public function getFormType(): string
    {
        return UserType::class;
    }

    public function getRouterPrefix(): string
    {
        return 'user';
    }
}