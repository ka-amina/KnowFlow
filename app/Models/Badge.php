<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['name', 'description', 'icon'];
    public function rules()
    {
        return $this->hasMany(BadgeRule::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, BadgeUser::class);
    }
}
