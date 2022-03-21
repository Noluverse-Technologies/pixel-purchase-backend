<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'is_pixel_purchased', 'is_license_purchased',
        'is_withdrawal_amount_paid',
        'is_reward_claimed',
        'license_id',
        'pixel_id',
        'pixel_amount',
        'license_amount',
        'withdrawal_fee_amount',
        'reward_claimed_amount',
        'user_id',
        'date',
        'is_nolu_plus_purchased',
        'nolu_plus_subscription_id',
        'nolu_plus_amount',
        'nolu_plus_bonus_amount'
    ];

    protected $hidden = [
        "created_at", "updated_at", "deleted_at",
    ];


    public function hasUser()
    {
        return $this->belongsTo('Modules\User\Entities\User', 'user_id');
    }

    public function hasPixel()
    {
        return $this->hasOne('Modules\Pixels\Entities\PixelPackages', 'id', 'pixel_id');
    }

    public function hasLicense()
    {
        return $this->hasOne('Modules\License\Entities\LicensePackages', 'id', 'license_id');
    }

    public function hasNoluPlusSubscription()
    {
        return $this->hasOne('Modules\Subscriptions\Entities\NoluPlusSubscriptoin', 'id', 'nolu_plus_subscription_id');
    }
}
