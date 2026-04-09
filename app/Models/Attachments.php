<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachments extends Model
{
    use HasFactory;
     // Operations table
    protected $table = 'attachments';
    
    // Fillable fields
    protected $fillable = [
        'media_name', 'media_path', 'media_url', 'user_id'
    ];
}
