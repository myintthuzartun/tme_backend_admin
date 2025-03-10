<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorLevel extends Model
{
    use HasFactory;

    protected $table = 'vendor_level';

    protected $fillable = [
        'level_en',
        'level_myan',
        'level_thai',
        'level_description_en',
        'level_description_myan',
        'level_description_thai',
        'benefits_en',
        'benefits_myan',
        'benefits_thai',
    ];
}
