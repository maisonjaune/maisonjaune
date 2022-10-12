<?php

namespace App\Admin;

use App\Controller\Admin\UserController;
use App\Entity\User;
use App\Form\UserType;
use Tournikoti\CrudBundle\Admin\AdminCRUD;
use Tournikoti\CrudBundle\Configuration\ConfigurationListInterface;
use Tournikoti\CrudBundle\Configuration\Model\Type\ArrayField;
use Tournikoti\CrudBundle\Configuration\Model\Type\BooleanField;
use Tournikoti\CrudBundle\Configuration\Model\Type\DateTimeField;
use Tournikoti\CrudBundle\Configuration\Model\Type\StringField;

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