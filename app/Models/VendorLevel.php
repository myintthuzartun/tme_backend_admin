<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorLevel extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's naming convention)
    protected $table = 'vendor_level';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'vendor_level',
        'level_description',
        'benefits',
    ];
}
