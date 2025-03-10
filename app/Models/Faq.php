<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $table = 'faq'; // Ensure this matches your actual table name
    protected $fillable = [ 'question', 
                            'answer'];
}
