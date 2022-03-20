<?php

namespace Modules\Subscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriptions extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pixel_id',
        'user_id',
        'license_id',
        'pixel_purchase_date',
        'license_purchase_date',
        'withdrawal_amount_is_paid',
        'has_expired',
        'nolu_reward_amount',
        'usdt_reward_amount',
        'subscription_type',
        'license_duration',
        'license_expiration_date'

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Subscription belongs to user
     */
    public function hasUser()
    {
        return $this->belongsTo('Modules\Users\Entities\Users', 'user_id');
    }

    /**
     * Subscription belongs to pixels
     */
    public function hasPixel()
    {
        return $this->belongsTo('Modules\Pixels\Entities\PixelPackages', 'pixel_id');
    }
    /**
     * Subscription and license relationship
     */
    public function hasLicense()
    {
        return $this->belongsTo('Modules\License\Entities\LicensePackages', 'license_id');
    }
}
