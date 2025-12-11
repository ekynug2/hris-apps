<?php

namespace App\Policies;

use App\Models\User;

class PayrollItemPolicy extends BaseHrisPolicy
{
    protected ?string $modelName = 'payroll_items';
}
