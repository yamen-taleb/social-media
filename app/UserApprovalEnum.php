<?php

namespace App;

enum UserApprovalEnum: string
{
    case APPROVED = 'approved';
    case PENDING = 'pending';
}
