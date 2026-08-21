<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | CATEGORY
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_category_id')
                ->nullable()
                ->constrained('product_categories')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | BASIC INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('slug')
                ->unique();

            $table->string('sku')
                ->unique();

            $table->text('description')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | PRICING
            |--------------------------------------------------------------------------
            */

            $table->decimal('price', 12, 2)
                ->default(0);

            $table->decimal('sale_price', 12, 2)
                ->nullable();

            $table->decimal('cost_price', 12, 2)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | INVENTORY
            |--------------------------------------------------------------------------
            */

            $table->integer('stock_quantity')
                ->default(0);

            $table->integer('low_stock_threshold')
                ->default(5);


            /*
            |--------------------------------------------------------------------------
            | PRODUCT IMAGE
            |--------------------------------------------------------------------------
            */

            $table->string('image')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true);


            /*
            |--------------------------------------------------------------------------
            | FEATURED
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_featured')
                ->default(false);


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};