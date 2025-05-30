<?php

namespace App\Enums;

enum SchoolAccountStatusEnum : string
{
    case PENDING = 'pending';
    case VALIDATING = 'validating';
    case APPROVED = 'approved';
}
