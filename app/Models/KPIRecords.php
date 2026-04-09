<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class KPIRecords extends Model
{
     use HasFactory;
     // Operations table
    protected $table = 'kpi_records';
    
    // Fillable fields
    protected $fillable = [
        'tbdate', 'kpi_name', 'amount', 'status', 'user_id', 'assign_by'
    ];
}
