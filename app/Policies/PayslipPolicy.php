<?php

namespace App\Policies;

use App\Models\User;

class PayslipPolicy extends BaseHrisPolicy
{
    protected ?string $modelName = 'payslips';
}
