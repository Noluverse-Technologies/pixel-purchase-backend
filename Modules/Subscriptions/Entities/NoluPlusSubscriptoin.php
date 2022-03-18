<?php

namespace Modules\Subscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoluPlusSubscriptoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_date',
        'expiration_date',
        'user_id',
        'has_expired',
        'nolu_plus_package_id'
    ];

    protected $hidden = [
        "created_at", "updated_at", "deleted_at",
    ];

    public function hasUser()
    {
        return $this->belongsTo('Modules\User\Entities\User', 'user_id');
    }

    public function hasNoluPlusPackage()
    {
        return $this->belongsTo('Modules\NoluPlus\Entities\NoluPlusPackage', 'nolu_plus_package_id');
    }
}
