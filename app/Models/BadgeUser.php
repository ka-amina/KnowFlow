<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeUser extends Model
{
    protected $fillable = ['user_id', 'badge_id', 'achievement_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }
}
