<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    public $timestamps = false; 

    protected $fillable=[
        'title',
        'description',
        'url',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
