<?php

namespace App\Policies;

use App\Models\User;

class PayrollAdjustmentPolicy extends BaseHrisPolicy
{
    protected ?string $modelName = 'payroll_adjustments';
}
