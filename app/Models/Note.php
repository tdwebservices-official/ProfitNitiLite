<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;
     // Operations table
    protected $table = 'notes';
    
    // Fillable fields
    protected $fillable = [
        't_month','target', 'action_steps', 'person_responsible', 'target_date', 'status', 'remarks' , 'user_id' 
    ];
}