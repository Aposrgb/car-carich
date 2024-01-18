<?php

namespace App\Helper\Enum\Role;

enum User: string
{
    case ADMIN = 'ROLE_ADMIN';
    case USER = 'ROLE_USER';
}