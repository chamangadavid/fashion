<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    
use HasFactory;

    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'low_stock_threshold',
        'image',
        'is_active',
        'is_featured',
    ];

   protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

     /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

     public function category()
    {
        return $this->belongsTo(
            ProductCategory::class,
            'product_category_id'
        );
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    public function inventoryAudits(): HasMany
    {
        return $this->hasMany(InventoryAudit::class);
    }

    public function collections(): BelongsToMany
{
    return $this->belongsToMany(
        Collection::class,
        'collection_product'
    )
    ->withPivot('sort_order')
    ->withTimestamps();
}

    /*
    |--------------------------------------------------------------------------
    | AUTOMATIC SLUG
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {

            if (empty($product->slug)) {

                $product->slug = Str::slug($product->name);

            }

        });
    }




}
