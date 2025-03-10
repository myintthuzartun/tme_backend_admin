<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currency';  
    // Make sure the column names match the migration structure
    protected $fillable = [ 'en_currency', 
                            'myan_currency', 
                            'exchange_rate', 
                            'thai_exchange_rate'];
}
