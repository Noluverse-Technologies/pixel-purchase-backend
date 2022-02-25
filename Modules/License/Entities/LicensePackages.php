<?php

namespace Modules\License\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pixels\Entities\PixelPackages;

class LicensePackages extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "short_name",
        "code",
        "image",
        "price",
        "currency",
        "expiration_date",
        "is_active",
        "pixel_id"
    ];

    protected $hidden = ["created_at", "updated_at", "deleted_at"];


    /**
     * Get the license package's pixel.
     */
    public function hasPixel()
    {
        return $this->belongsTo('Modules\Pixels\Entities\PixelPackages', 'pixel_id');
    }
}
