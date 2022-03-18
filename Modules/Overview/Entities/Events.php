<?php

namespace Modules\Overview\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'date_from',
        'date_till',
        'event_link'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static function newFactory()
    {
        return \Modules\Overview\Database\factories\EventsFactory::new();
    }
}
