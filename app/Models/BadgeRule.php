<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeRule extends Model
{
    protected $fillable = ['badge_id', 'user_type', 'rule_condition', 'rule_operator', 'rule_value'];
}
