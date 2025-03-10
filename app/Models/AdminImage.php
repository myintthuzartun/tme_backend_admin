<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminImage extends Model
{
    use HasFactory;
    protected $table = 'admin_image_path'; // Ensure this matches your actual table name
    protected $fillable = ['image_path'];
}
