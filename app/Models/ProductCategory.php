<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProductCategory extends Model
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

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

      /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

        /*
    |--------------------------------------------------------------------------
    | Automatically create slug
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function ($category) {

            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }

        });

        static::updating(function ($category) {

            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }

        });
    }



}
