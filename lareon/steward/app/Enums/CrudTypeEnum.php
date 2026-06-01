<?php

namespace Lareon\Steward\App\Enums;

enum CrudTypeEnum: string
{
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
