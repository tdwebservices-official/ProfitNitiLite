<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserMeta extends Model
{
    use HasFactory;
     // Operations table
    protected $table = 'user_meta';
    
    // Fillable fields
    protected $fillable = [
        'user_id', 'meta_key', 'meta_value'
    ];
}