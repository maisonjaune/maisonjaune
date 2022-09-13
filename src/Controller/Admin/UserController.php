<?php

namespace App\Controller\Admin;

use App\Admin\UserAdmin;
use App\Service\Admin\CRUDController;

class UserController extends CRUDController
{
    public function __construct(UserAdmin $admin)
    {
        $this->admin = $admin;
    }
}
