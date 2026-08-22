<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    
protected $fillable = [

        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'is_featured',
        'sort_order',

    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'is_active' => 'boolean',

        'is_featured' => 'boolean',

        'sort_order' => 'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'collection_product'
        )
        ->withPivot('sort_order')
        ->withTimestamps()
        ->orderBy('collection_product.sort_order');
    }
}
