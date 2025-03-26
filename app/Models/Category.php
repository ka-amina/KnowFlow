<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable=['name','parent_id'];

    public function subcategories(){
        return $this->hasMany(Category::class,'subcategories');
    }

    public function parent(){
        return $this->belongsTo(Category::class,'subcategories');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    
}
