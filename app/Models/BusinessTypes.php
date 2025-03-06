<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessTypes extends Model
{
    use HasFactory;

    protected $table = 'business_types'; // Ensure this matches your actual table name

    protected $fillable = [
        'id',
        'business_name',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    // Uncomment if you don't want timestamps
    // public $timestamps = false;
}

