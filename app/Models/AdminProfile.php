<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    use HasFactory;

    protected $table = 'admin_profile'; // Explicitly set the table name

    protected $fillable = [
        'user_id', 'about', 'company', 'job', 'country', 'address', 'phone', 'twitter', 'facebook', 'instagram', 'linkedin'
    ];
    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
