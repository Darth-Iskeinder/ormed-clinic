<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefits extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_name',
        'total_revenue',
        'total_employee_benefits',
        'total_company_benefits'
    ];
}
