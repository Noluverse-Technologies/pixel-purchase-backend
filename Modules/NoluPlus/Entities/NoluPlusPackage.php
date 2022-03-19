<?php

namespace Modules\NoluPlus\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoluPlusPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'duration_in_days',
        'discount_percentage',
        'price',
        'currency',
        'withdrawal_fee',
        'discount_on_stores'
    ];

    protected $hidden = [
        "created_at", "updated_at", "deleted_at",
    ];
}
