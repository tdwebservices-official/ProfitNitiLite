<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Options extends Model
{
    use HasFactory;
     // Operations table
    protected $table = 'options';
    
    // Fillable fields
    protected $fillable = [
        'user_id', 'option_name', 'option_value'
    ];
}
