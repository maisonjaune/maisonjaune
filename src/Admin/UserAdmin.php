<?php

namespace App\Admin;

use App\Controller\Admin\UserController;
use App\Entity\User;
use App\Form\Admin\UserType;
use Tournikoti\CrudBundle\Admin\AdminCRUD;
use Tournikoti\CrudBundle\Configuration\ConfigurationListInterface;
use Tournikoti\CrudBundle\Configuration\Model\Type\BooleanField;
use Tournikoti\CrudBundle\Configuration\Model\Type\DateTimeField;
use Tournikoti\CrudBundle\Configuration\Model\Type\EnumField;
use Tournikoti\CrudBundle\Configuration\Model\Type\StringField;

class UserAdmin extends AdminCRUD
{
    public function configurationList(ConfigurationListInterface $configurationList): void
    {
        $configurationList
            ->add(new StringField('id', 'Id'))
            ->add(new EnumField('civility', 'Civility'))
            ->add(new StringField('firstname', 'Firstname'))
            ->add(new StringField('lastname', 'Lastname'))
            ->add(new StringField('email', 'Email'))
            ->add(new BooleanField('isActif', 'Is Actif'))
            ->add(new BooleanField('isMember', 'Is Member'))
            ->add(new DateTimeField('lastConnectionAt', 'Last Connection At'))
            ->add(new StringField('password', 'Password'))
            ->add(new DateTimeField('createdAt', 'Created At'))
            ->add(new DateTimeField('updatedAt', 'Updated At'));
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