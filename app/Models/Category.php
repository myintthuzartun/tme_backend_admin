<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'parent_id', 
        'name', 
        'slug', 
        'description', 
        'commission_rate', 
        'image', 
        'icon', 
        'status'
    ];

    // Get parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Get child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Get products in category
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
