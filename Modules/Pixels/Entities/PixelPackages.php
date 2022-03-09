<?php

namespace Modules\Pixels\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class PixelPackages extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'short_name', 'code', 'image', 'price', 'currency', 'is_active', 'license_id', 'duration_in_days', 'type'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];
}
