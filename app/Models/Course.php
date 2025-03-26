<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'level', 'status', 'category_id','teacher_id','price'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'course_tag');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
