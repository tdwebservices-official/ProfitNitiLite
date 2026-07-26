<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIHistory extends Model
{
    use HasFactory;
     // Operations table
    protected $table = 'ai_history';
    
    // Fillable fields
    protected $fillable = [
        'run_date', 'history_name', 'history_value', 'status', 'assign_by', 'user_id'
    ];
}