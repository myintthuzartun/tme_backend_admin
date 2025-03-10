<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddBusinessType extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's naming convention)
    protected $table = 'business_type';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'business_name_en',
        'business_name_myan',
        'business_name_thai',
    ];
}