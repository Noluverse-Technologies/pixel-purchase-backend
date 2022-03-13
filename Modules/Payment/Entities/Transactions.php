<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        "type", "date", "amount", "user_id"
    ];

    protected $hidden = [
        "created_at", "updated_at", "deleted_at",
    ];


    public function hasUser()
    {
        return $this->belongsTo('Modules\User\Entities\User', 'user_id');
    }
}
