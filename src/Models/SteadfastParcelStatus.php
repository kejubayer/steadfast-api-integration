<?php

declare(strict_types=1);

namespace Kejubayer\Steadfast\Models;

use Illuminate\Database\Eloquent\Model;

class SteadfastParcelStatus extends Model
{
    protected $table = 'steadfast_parcel_statuses';

    protected $fillable = [
        'notification_type',
        'consignment_id',
        'invoice',
        'cod_amount',
        'status',
        'delivery_charge',
        'tracking_message',
        'provider_updated_at',
        'payload',
    ];

    protected $casts = [
        'cod_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'provider_updated_at' => 'datetime',
        'payload' => 'array',
    ];
}
