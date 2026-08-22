<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    
protected $fillable = [

        'user_id',

        'order_number',

        'status',

        'customer_email',

        'customer_phone',

        'shipping_first_name',

        'shipping_last_name',

        'shipping_address',

        'shipping_city',

        'shipping_country',

        'subtotal',

        'shipping_amount',

        'total_amount',

        'payment_method',

        'payment_status',

        'payment_reference',

        'notes',

    ];


    protected $casts = [

        'subtotal' => 'decimal:2',

        'shipping_amount' => 'decimal:2',

        'total_amount' => 'decimal:2',

    ];


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER ITEMS
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
