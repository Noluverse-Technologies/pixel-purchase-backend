<?php

namespace Modules\NoluPlus\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoluPlusPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_in_days',
        'discount_percentage',
        'price',
        'currency'
    ];

    protected $hidden = [
        "created_at", "updated_at", "deleted_at",
    ];
}
