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
        'pixel_amount',
        'license_amount',
        'withdrawal_fee_amount',
        'reward_claimed_amount',
        'user_id',
        'date'
    ];

    protected $hidden = [
        "created_at", "updated_at", "deleted_at",
    ];


    public function hasUser()
    {
        return $this->belongsTo('Modules\User\Entities\User', 'user_id');
    }
}
