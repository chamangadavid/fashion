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
        Schema::create('collection_product', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | COLLECTION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('collection_id')
                ->constrained('collections')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | SORT ORDER
            |--------------------------------------------------------------------------
            */

            $table->integer('sort_order')
                ->default(0);


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | PREVENT DUPLICATES
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'collection_id',
                'product_id'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_product');
    }
};