<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiTarget extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'kpi_type',
        'target_minutes',
    ];
}
