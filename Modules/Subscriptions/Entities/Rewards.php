<?php

namespace Modules\Subscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rewards extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Subscriptions\Database\factories\RewardsFactory::new();
    }
}
