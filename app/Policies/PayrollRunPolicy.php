<?php

namespace App\Policies;

use App\Models\User;

class PayrollRunPolicy extends BaseHrisPolicy
{
    protected ?string $modelName = 'payroll_runs';
}
